<?php declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Entity\NRPZS\HealthcareFacility;
use App\Exception\MissingParameterException;
use App\Service\Filter\Direction;
use App\Service\Filter\Healthcare\HealthcareFilter;
use App\Service\Filter\Healthcare\OrderBy;
use App\Service\Filter\Healthcare\OrderFilterModifier;
use App\Service\Filter\Healthcare\SearchBy;
use App\Service\Filter\Healthcare\SearchFilterModifier;
use App\Service\Filter\PaginatorFilterModifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/healthcare', name: 'api_healthcare_')]
class HealthcareController extends BaseApiController
{

	#[Route('/list', name: 'list')]
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
				return $this->error('Invalid order by, valid values are: ' . implode(', ', OrderBy::cases()));
			}
			if (isset($params["direction"])) {
				$direction = Direction::tryFrom($params["direction"]);
				if ($direction === null) {
					return $this->error('Invalid direction, valid values are: ' . implode(', ', Direction::cases()));
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
