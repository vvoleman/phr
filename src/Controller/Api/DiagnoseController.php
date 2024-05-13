<?php
declare(strict_types=1);


namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Exception\MissingParameterException;
use App\OpenApi\ApiErrorResponse;
use App\OpenApi\ApiSuccessResponse;
use App\Repository\UZIS\DiagnoseRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class DiagnoseController extends BaseApiController
{

	#[Route('/api/diagnose/list', name: 'api_diagnose_search', methods: ['GET'])]
	#[OA\Get(
		description: 'Search ICN-10 diagnoses by their ID. Returns an array of diagnoses.',
		summary: 'Search diagnoses',
		tags: ['Diagnose'],
	)]
	#[OA\Parameter(
		name: 'page',
		description: 'Page number, starts from 0',
		in: 'query',
		required: false,
		schema: new OA\Schema(
			type: 'integer'
		),
		example: 0
	)]
	#[OA\Parameter(
		name: 'search',
		description: 'Search query',
		in: 'query',
		required: false,
		schema: new OA\Schema(
			type: 'string'
		),
		example: 'kotník'
	)]
	#[OA\Parameter(
		name: 'order_by',
		description: 'Order by attribute',
		in: 'query',
		required: false,
		schema: new OA\Schema(
			type: 'string',enum: ['id', 'name']
		),
		example: 'name'
	)]
	#[OA\Parameter(
		name: 'direction',
		description: 'Order direction',
		in: 'query',
		required: false,
		schema: new OA\Schema(
			type: 'string',enum: ['asc', 'desc']
		),
		example: 'asc'
	)]
	#[ApiSuccessResponse(
		description: 'Returns first 10 diagnoses found for given query',
		items: new OA\Items(
			properties: [
				new OA\Property(property: 'id', description: 'ICD-10 code', type: 'string', example: 'W10'),
				new OA\Property(property: 'name', description: 'Diagnose name', type: 'string', example: 'Pád na schodech a stupních nebo z nich'),
				new OA\Property(property: 'parent', properties: [
					new OA\Property(property: 'id', description: 'ICD-10 parent group code', type: 'string', example: 'XX'),
					new OA\Property(property: 'name', description: 'Parent group name', type: 'string', example: 'Stav související se životním stylem'),
				], type: 'object'),
			],
		)
	)]
	#[ApiErrorResponse(
		description: 'Response given when missing "search" attribute',
		message: 'Missing parameters',
	)]
	public function index(DiagnoseRepository $repository): JsonResponse
	{
		try {
			$params = $this->getParams([
				"page" => false,
				"search" => false,
				"order_by" => false,
				"direction" => false
			]);
		} catch (MissingParameterException) {
			return $this->error("Missing parameters");
		}

		$direction = match ($params["direction"] ?? null) {
			"desc" => "desc",
			default => "asc"
		};

		$sortBy = match ($params['order_by'] ?? null) {
			"id" => "id",
			default => "name"
		};

		$page = (int)($params["page"] ?? 0);
		$search = $params["search"] ?? "";

		$diagnoses = $repository->search($search, $page, $sortBy, $direction);
		$mapped = array_map(function ($diagnose) {
			return $diagnose->toArray();
		}, $diagnoses);

		return $this->send($mapped);
	}

	#[Route('/api/diagnose/multiple', name: 'api_diagnose_multiple', methods: ['GET'])]
	#[OA\Get(
		description: 'Search ICN-10 diagnoses by multiple IDs. Returns an array of diagnoses.',
		summary: 'Search diagnoses by multiple IDs',
		tags: ['Diagnose'],
	)]
	#[OA\Parameter(
		name: 'ids[]',
		description: 'List of IDs to search for',
		in: 'query',
		required: true,
		schema: new OA\Schema(
			type: 'array',
			items: new OA\Items(
				type: 'string'
			)
		),
		example: ['W10', 'W11']
	)]
	#[ApiSuccessResponse(
		description: 'Returns diagnoses for given IDs',
		items: new OA\Items(
			properties: [
				new OA\Property(property: 'id', description: 'ICD-10 code', type: 'string', example: 'W10'),
				new OA\Property(property: 'name', description: 'Diagnose name', type: 'string', example: 'Pád na schodech a stupních nebo z nich'),
				new OA\Property(property: 'parent', properties: [
					new OA\Property(property: 'id', description: 'ICD-10 parent group code', type: 'string', example: 'XX'),
					new OA\Property(property: 'name', description: 'Parent group name', type: 'string', example: 'Stav související se životním stylem'),
				], type: 'object'),
			],
		)
	)]
	#[ApiErrorResponse(
		description: 'Response given when missing "ids" attribute',
		message: 'Missing parameters',
	)]
	public function multiple(DiagnoseRepository $repository): JsonResponse {
		try {
			$params = $this->getParams([
				"ids" => true,
			]);
		} catch (MissingParameterException) {
			return $this->error("Missing parameters");
		}

		if (!is_array($params["ids"])) {
			return $this->error("Parameter 'ids' must be an array");
		}

		$ids = $params["ids"];

		$diagnoses = $repository->getByIds($ids);

		$mapped = array_map(function ($diagnose) {
			return $diagnose->toArray();
		}, $diagnoses);

		return $this->send($mapped);
	}

}
