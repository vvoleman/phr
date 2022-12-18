<?php
declare(strict_types=1);


namespace App\Service\Filter\MedicalProduct;

use App\Service\Filter\IFilterModifier;
use Doctrine\ORM\QueryBuilder;

class SearchFilterModifier implements IFilterModifier
{

	private string $search;

	public function __construct(string $search)
	{
		$this->search = $search;
	}

	public function process(QueryBuilder $builder): QueryBuilder
	{
		// search by name or addition
		$builder->andWhere("mp.name LIKE :search OR mp.addition LIKE :search");
		$builder->setParameter("search", "%{$this->search}%");

		return $builder;
	}


}