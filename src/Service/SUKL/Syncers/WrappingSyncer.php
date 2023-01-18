<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\Wrapping;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class WrappingSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_obaly.csv';

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(Wrapping::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$wrapping = $repository->find($row['OBAL']);

		if ($wrapping === null) {
			$wrapping = new Wrapping();
			$wrapping->setId($row['OBAL']);
			$this->entityManager->persist($wrapping);
		}

		$wrapping->setName($row['NAZEV']);
		$wrapping->setNameEn($row['NAZEV_EN']);
		$wrapping->setEdqmCode($row['KOD_EDQM']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}