<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\Doping;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class DopingSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_doping.csv';

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(Doping::class);
	}

	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$doping = $repository->find($row['DOPING']);

		$changed = false;
		if ($doping === null) {
			$doping = new Doping();
			$doping->setId($row['DOPING']);

			$this->entityManager->persist($doping);
		}

		$doping->setName($row['NAZEV']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}