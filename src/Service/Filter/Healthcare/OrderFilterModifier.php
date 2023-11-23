<?php declare(strict_types=1);

namespace App\Service\Filter\Healthcare;

use App\Service\Filter\Direction;
use App\Service\Filter\IFilterModifier;
use Doctrine\ORM\QueryBuilder;

class OrderFilterModifier implements IFilterModifier
{

	private OrderBy $orderBy;
	private Direction $direction;

	public function __construct(OrderBy $orderBy, Direction $direction)
	{
		$this->orderBy = $orderBy;
		$this->direction = $direction;
	}

	public function process(QueryBuilder $builder): QueryBuilder
	{
		$builder->orderBy("hf.{$this->orderBy->value}", $this->direction->value);

		return $builder;
	}
}
