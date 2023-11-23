<?php declare(strict_types=1);

namespace App\Service\Filter\Healthcare;

use App\Repository\HealthcareFacilityRepository;
use App\Service\Filter\AbstractFilter;
use Doctrine\ORM\QueryBuilder;

class HealthcareFilter extends AbstractFilter
{
	public function __construct(private readonly HealthcareFacilityRepository $repository) { }


	protected function getQueryBuilder(): QueryBuilder
	{
		return $this->repository->createQueryBuilder("hf")
			->leftJoin("hf.services", "hs");
	}
}
