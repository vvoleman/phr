<?php
declare(strict_types=1);

namespace App\Service\Filter;

use Doctrine\ORM\QueryBuilder;

interface IFilterModifier
{

	public function process(QueryBuilder $builder): QueryBuilder;

}