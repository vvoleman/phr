<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\Dispensing;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class DispensingSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_vydej.csv';

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(Dispensing::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$dispensing = $repository->find($row['VYDEJ']);
		if ($dispensing === null) {
			$dispensing = new Dispensing();
			$dispensing->setId($row['VYDEJ']);
			$this->entityManager->persist($dispensing);
		}

		$dispensing->setName($row['NAZEV']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}