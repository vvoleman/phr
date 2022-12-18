<?php
declare(strict_types=1);


namespace App\Service\Filter\MedicalProduct;

use App\Repository\MedicalProductRepository;
use Doctrine\ORM\QueryBuilder;

class MedicalProductFilter extends \App\Service\Filter\AbstractFilter
{

	public function __construct(private readonly MedicalProductRepository $repository) { }

	protected function getQueryBuilder(): QueryBuilder
	{
		return $this->repository->createQueryBuilder("mp");
	}
}