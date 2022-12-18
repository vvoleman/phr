<?php

declare(strict_types=1);

namespace App\Service\Filter;

use Doctrine\ORM\QueryBuilder;

abstract class AbstractFilter
{

	/** @var IFilterModifier[] */
	protected array $modifiers = [];

	public function addModifier(IFilterModifier $modifier): void
	{
		$this->modifiers[] = $modifier;
	}

	public function setModifiers(array $modifiers): void
	{
		$this->modifiers = $modifiers;
	}

	public function run(): array
	{
		$qb = $this->getQueryBuilder();

		foreach ($this->modifiers as $modifier) {
			$qb = $modifier->process($qb);
		}

		//dd($qb->getQuery()->getSQL(), $qb->getParameters());

		return $qb
			->getQuery()
			->getResult();
	}

	protected abstract function getQueryBuilder(): QueryBuilder;


}