<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Entity\NRPZS\HealthcareFacility;
use App\Exception\MissingParameterException;
use App\OpenApi\ApiErrorResponse;
use App\OpenApi\ApiSuccessResponse;
use App\Service\Filter\Direction;
use App\Service\Filter\Healthcare\HealthcareFilter;
use App\Service\Filter\Healthcare\OrderBy;
use App\Service\Filter\Healthcare\OrderFilterModifier;
use App\Service\Filter\Healthcare\SearchBy;
use App\Service\Filter\Healthcare\SearchFilterModifier;
use App\Service\Filter\PaginatorFilterModifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class HealthcareController extends BaseApiController
{

	#[Route('/api/healthcare/list', name: 'api_healthcare_list', methods: ['GET'])]
	#[OA\Get(
		description: 'Search medical facilities. Returns an array of medical facilities.',
		summary: 'Search medical facilities',
		tags: ['Healthcare'],
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
		name: 'order_by',
		description: 'Order by attribute',
		in: 'query',
		required: false,
		schema: new OA\Schema(
			type: 'string', enum: ['id', 'fullName', 'city', 'street', 'activityStartedAt']
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
	#[OA\Parameter(
		name: 'any',
		description: 'Search query in any field',
		in: 'query',
		required: false,
		schema: new OA\Schema(
			type: 'string'
		),
		example: 'Krajská zdravotní'
	)]
	#[ApiSuccessResponse(
		description: 'List of medical facilities',
		items: new OA\Items(
			properties:[]
		)
	)]
	public function list(HealthcareFilter $filter): JsonResponse
	{
		try {
			$options = [
				"page" => false,
				"search" => false,
				"order_by" => false,
				"direction" => false
			];
			foreach (SearchBy::cases() as $case) {
				$options[strtolower($case->name)] = false;
			}
			$params = $this->getParams($options);
		} catch (MissingParameterException) {
			return $this->error("Missing parameters");
		}

		$page = (int)($params["page"] ?? '0');
		$filter->addModifier(new PaginatorFilterModifier($page));

		$searchFilter = SearchFilterModifier::fromParams($params);
		if ($searchFilter !== null) {
			$filter->addModifier($searchFilter);
		}

		// Order by filter
		if (isset($params["order_by"])) {
			$orderBy = OrderBy::tryFrom($params["order_by"]);
			if ($orderBy === null) {
				return $this->error('Invalid order by');
			}
			if (isset($params["direction"])) {
				$direction = Direction::tryFrom(strtoupper($params["direction"]));
				if ($direction === null) {
					return $this->error('Invalid direction');
				}
			} else {
				$direction = Direction::ASC;
			}

			$filter->addModifier(new OrderFilterModifier($orderBy, $direction));
		}

		/** @var HealthcareFacility[] $products */
		$products = $filter->run();
		$products = array_map(fn(HealthcareFacility $product) => $product->serialize(), $products);

		return $this->send($products);

	}
}
