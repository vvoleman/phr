<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\IndicationGroup;
use Doctrine\ORM\EntityRepository;

class IndicationGroupSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_indikacniskupiny.csv';

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(IndicationGroup::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$group = $repository->find($row['INDSK']);

		if ($group === null) {
			$group = new IndicationGroup();
			$group->setId($row['INDSK']);
			$this->entityManager->persist($group);
		}

		$group->setName($row['NAZEV']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}