<?php
declare(strict_types=1);


namespace App\Controller\Api;

use App\Controller\BaseApiController;
use App\Entity\MedicalProduct;
use App\Exception\MissingParameterException;
use App\Service\Filter\Direction;
use App\Service\Filter\MedicalProduct\MedicalProductFilter;
use App\Service\Filter\MedicalProduct\OrderBy;
use App\Service\Filter\MedicalProduct\OrderFilterModifier;
use App\Service\Filter\MedicalProduct\PaginatorFilterModifier;
use App\Service\Filter\MedicalProduct\SearchFilterModifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/medical-product', name: 'api_medical_product_')]
class MedicalProductController extends BaseApiController
{

	#[Route('/list', name: 'list')]
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
				$direction = Direction::tryFrom($params["direction"]);
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

}