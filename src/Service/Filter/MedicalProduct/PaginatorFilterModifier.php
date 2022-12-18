<?php
declare(strict_types=1);


namespace App\Service\Filter\MedicalProduct;

use Doctrine\ORM\QueryBuilder;

class PaginatorFilterModifier implements \App\Service\Filter\IFilterModifier
{

	public const LIMIT = 10;

	private int $page;
	private int $limit;

	public function __construct(int $page, int $limit = self::LIMIT)
	{
		$this->page = $page;
		$this->limit = $limit;
	}

	public function process(QueryBuilder $builder): QueryBuilder
	{
		$builder->setFirstResult($this->page * $this->limit);
		$builder->setMaxResults($this->limit);

		return $builder;
	}
}