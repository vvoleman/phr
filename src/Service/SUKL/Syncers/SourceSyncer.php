<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\Source;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class SourceSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_zdroje.csv';

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(Source::class);
	}

	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$source = $repository->find($row['ZDROJ']);

		if ($source === null) {
			$source = new Source();
			$source->setId($row['ZDROJ']);

			$this->entityManager->persist($source);
		}

		$source->setName($row['NAZEV']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}