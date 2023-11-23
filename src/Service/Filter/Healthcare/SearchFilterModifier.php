<?php declare(strict_types=1);

namespace App\Service\Filter\Healthcare;

use App\Service\Filter\IFilterModifier;
use Doctrine\ORM\QueryBuilder;

class SearchFilterModifier implements IFilterModifier
{

	public function process(QueryBuilder $builder): QueryBuilder
	{
		// TODO: Implement process() method.
	}
}
