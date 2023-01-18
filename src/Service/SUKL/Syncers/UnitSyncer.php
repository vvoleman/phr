<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\Unit;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class UnitSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_jednotky.csv';

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(Unit::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$unit = $repository->find($row['UN']);

		if ($unit === null) {
			$unit = new Unit();
			$unit->setId($row['UN']);
			$this->entityManager->persist($unit);
		}

		$unit->setName($row['NAZEV']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}