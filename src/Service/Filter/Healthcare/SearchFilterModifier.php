<?php declare(strict_types=1);

namespace App\Service\Filter\Healthcare;

use App\Entity\NRPZS\HealthcareFacility;
use App\Entity\NRPZS\HealthcareService;
use App\Service\Filter\IFilterModifier;
use Doctrine\ORM\QueryBuilder;

class SearchFilterModifier implements IFilterModifier
{
	/** @var array{type: SearchBy, value: string}[]  */
	private array $searches;

	/**
	 * @param array{type: SearchBy, value: string}[] $searches
	 */
	public function __construct(array $searches)
	{
		$this->searches = $searches;
	}

	public function process(QueryBuilder $builder): QueryBuilder
	{
		$facilityFields = $builder->getEntityManager()->getClassMetadata(HealthcareFacility::class)->getFieldNames();
		$serviceFields = $builder->getEntityManager()->getClassMetadata(HealthcareService::class)->getFieldNames();

		$fields = [];
		foreach ($facilityFields as $field) {
			$fields[] = "hf.{$field}";
		}
		foreach ($serviceFields as $field) {
			$fields[] = "hs.{$field}";
		}

		foreach ($this->searches as $search) {
			$type = $search['type'];
			$searchParam = "search_{$type->name}";
			if ($type === SearchBy::ANY) {
				$query = join(" OR ", array_map(fn($field) => "{$field} LIKE :{$searchParam}", $fields));
				$builder->andWhere($query);
			} else {
				$builder->andWhere("{$type->value} LIKE :{$searchParam}");
			}
			$builder->setParameter($searchParam, "%{$search['value']}%");
		}

		return $builder;
	}

	/**
	 * @param array{type: SearchBy, value: string}[] $params
	 * @return SearchFilterModifier|null
	 */
	public static function fromParams(array $params): ?self
	{
		$searches = [];
		$cases = SearchBy::cases();
		foreach ($cases as $case) {
			$type = strtolower($case->name);
			if (isset($params[$type])) {
				$searches[] = [
					'type' => $case,
					'value' => $params[$type]
				];
			}
		}

		if (count($searches) === 0) {
			return null;
		}

		return new self($searches);
	}
}
