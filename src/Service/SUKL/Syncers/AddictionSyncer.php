<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\Addiction;
use Doctrine\ORM\EntityRepository;

class AddictionSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_zavislost.csv';

	protected function getFilename(): string
	{
		return self::FILENAME;
	}

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(Addiction::class);
	}

	protected function handleRow(array $row, EntityRepository $repository): void
	{
		/** @var Addiction|null $addiction */
		$addiction = $repository->find($row['ZAV']);

		if ($addiction === null) {
			$addiction = new Addiction();
			$addiction->setId($row['ZAV']);
			$this->entityManager->persist($addiction);
		}

		$addiction->setName($row['NAZEV_CS']);
	}
}