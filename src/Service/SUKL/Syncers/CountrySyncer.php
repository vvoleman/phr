<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\Country;
use Doctrine\ORM\EntityRepository;

class CountrySyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_zeme.csv';

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(Country::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$country = $repository->find($row['ZEM']);

		if ($country === null) {
			$country = new Country();
			$country->setId($row['ZEM']);

			$this->entityManager->persist($country);
		}

		$country->setName($row['NAZEV']);
		$country->setNameEn($row['NAZEV_EN']);
		$country->setEdqmCode($row['KOD_EDQM']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}