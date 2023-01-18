<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\RegistrationStatus;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class RegistrationStatusSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_stavyreg.csv';

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(RegistrationStatus::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$status = $repository->find($row['REG']);

		if ($status === null) {
			$status = new RegistrationStatus();
			$status->setId($row['REG']);
			$this->entityManager->persist($status);
		}

		$status->setName($row['NAZEV']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}