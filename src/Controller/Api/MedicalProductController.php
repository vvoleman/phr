<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Entity\MedicalProduct;
use App\Exception\MissingParameterException;
use App\OpenApi\ApiErrorResponse;
use App\OpenApi\ApiSuccessResponse;
use App\Repository\MedicalProductRepository;
use App\Service\Filter\Direction;
use App\Service\Filter\MedicalProduct\MedicalProductFilter;
use App\Service\Filter\MedicalProduct\OrderBy;
use App\Service\Filter\MedicalProduct\OrderFilterModifier;
use App\Service\Filter\MedicalProduct\SearchFilterModifier;
use App\Service\Filter\PaginatorFilterModifier;
use App\Service\SUKL\Exception\SuklException;
use App\Service\SUKL\RetrieveProductDocument;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class MedicalProductController extends BaseApiController
{

	#[Route('/api/medical-product/list', name: 'api_medical_product_list', methods: ['GET'])]
	#[OA\Get(
		description: 'Search medical products their ID or name. Returns an array of medical products.',
		summary: 'Search medical products',
		tags: ['Medical products'],
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
		example: 'ibalgin'
	)]
	#[OA\Parameter(
		name: 'order_by',
		description: 'Order by attribute',
		in: 'query',
		required: false,
		schema: new OA\Schema(
			type: 'string', enum: ['id', 'name', 'expiration_hours', 'administration_method', 'strength']
		),
		example: 'name'
	)]
	#[OA\Parameter(
		name: 'direction',
		description: 'Order direction',
		in: 'query',
		required: false,
		schema: new OA\Schema(
			type: 'string', enum: ['asc', 'desc']
		),
		example: 'asc'
	)]
	#[ApiSuccessResponse(
		description: 'Returns first 10 medical products found for given query',
		items: new OA\Items(
			properties: [
				new OA\Property(property: 'id', description: 'ID', type: 'string', example: '0000009'),
				new OA\Property(property: 'name', description: 'Name', type: 'string', example: 'ACYLCOFFIN'),
				new OA\Property(property: 'packaging', properties: [
					new OA\Property(property: 'form', properties: [
						new OA\Property(property: 'id', description: 'Form ID', type: 'string', example: 'TBL NOB'),
						new OA\Property(property: 'name', description: 'Form name', type: 'string', example: 'Tableta'),
						new OA\Property(property: 'short_name', description: 'Form short name', type: 'string', example: 'Tableta'),
					], type: 'object'),
					new OA\Property(property: 'packaging', description: 'Packaging', type: 'string', example: '10'),
				], type: 'object'),
				new OA\Property(property: 'addition', description: 'Product detail in one string', type: 'string', example: '450MG/50MG TBL NOB 10'),
				new OA\Property(property: 'registrationHolder', description: 'Registration holder', type: 'string', example: 'ZNB'),
				new OA\Property(property: 'recentlyDelivered', description: 'Was medical product delivered in last 6 months?', type: 'boolean', example: true),
				new OA\Property(property: 'expirationHours', description: 'Expiration hours', type: 'integer', example: 25920),
				new OA\Property(property: 'country', description: 'Country of origin', type: 'string', example: 'SLOVENSKÁ REPUBLIKA'),
				new OA\Property(property: 'substances', properties: [
					new OA\Property(property: 'id', description: 'Substance ID', type: 'string'),
					new OA\Property(property: 'name', description: 'Substance name', type: 'string'),
					new OA\Property(property: 'strength', description: 'Substance strength', type: 'string'),
				]),
			],
		)
	)]
	#[ApiErrorResponse(
		description: 'Response given when missing "search" attribute',
		message: 'Missing parameters',
	)]
	public function list(MedicalProductFilter $filter): JsonResponse
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

		// Page filter
		$page = (int)($params["page"] ?? '0');
		$filter->addModifier(new PaginatorFilterModifier($page));

		// Search filter
		if (isset($params["search"])) {
			$filter->addModifier(new SearchFilterModifier($params["search"]));
		}

		// Order by filter
		if (isset($params["order_by"])) {
			$orderBy = OrderBy::tryFrom($params["order_by"]);
			if ($orderBy === null) {
				return $this->error('Invalid order by, valid values are: ' . implode(', ', OrderBy::cases()));
			}
			if (isset($params["direction"])) {
				$direction = Direction::tryFrom(strtoupper($params["direction"]));
				if ($direction === null) {
					return $this->error('Invalid direction, valid values are: ' . implode(', ', Direction::cases()));
				}
			} else {
				$direction = Direction::ASC;
			}

			$filter->addModifier(new OrderFilterModifier($orderBy, $direction));
		}


		/** @var MedicalProduct[] $products */
		$products = $filter->run();
		$products = array_map(fn(MedicalProduct $product) => $product->serialize(), $products);

		return $this->send($products);
	}

	#[Route('/api/medical-product/document', name: 'get', methods: ['GET'])]
	#[OA\Get(
		description: 'Get product document PDF by product ID. Redirects to the document URL. It works by retrieving document ID from SUKL page.',
		summary: 'Get product document',
		tags: ['Medical products'],
	)]
	#[OA\Parameter(
		name: 'id',
		description: 'Product ID',
		in: 'query',
		required: true,
		schema: new OA\Schema(
			type: 'string'
		),
		example: '0000009'
	)]
	#[ApiSuccessResponse(
		description: 'Redirects to the document URL',
		items: new OA\Items()
	)]
	#[ApiErrorResponse(
		description: 'Response given when missing "id" attribute',
		message: 'Missing parameters',
	)]
	public function productDocument(
		MedicalProductRepository $repository,
		RetrieveProductDocument  $retrieveProductDocument
	): Response
	{
		try {
			$params = $this->getParams([
				"id" => true
			]);
		} catch (MissingParameterException) {
			return $this->error("Missing parameters");
		}

		$product = $repository->find($params["id"]);

		if ($product === null) {
			return $this->error(sprintf("Product with ID %s not found", $params["id"]));
		}

		try {
			$link = $retrieveProductDocument->getDocumentUrl($product);

			if ($link === null) {
				return $this->error("No document found for product ID: " . $params["id"]);
			}

			return $this->redirect($link);
		} catch (SuklException) {
			return $this->error("Unable to retrieve document URL");
		}
	}

}
