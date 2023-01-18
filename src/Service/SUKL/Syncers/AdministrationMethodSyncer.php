<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\AdministrationMethod;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class AdministrationMethodSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_cesty.csv';

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(AdministrationMethod::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$method = $repository->find($row['CESTA']);

		if ($method === null) {
			$method = new AdministrationMethod();
			$method->setId($row['CESTA']);
			$this->entityManager->persist($method);
		}

		$method->setName($row['NAZEV']);
		$method->setNameEn($row['NAZEV_EN']);
		$method->setEdqmCode($row['KOD_EDQM']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}