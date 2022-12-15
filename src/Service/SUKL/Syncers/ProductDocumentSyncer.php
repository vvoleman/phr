<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\ProductDocument;
use Doctrine\ORM\EntityRepository;

class ProductDocumentSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_nazvydokumentu.csv';

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(ProductDocument::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$doc = $repository->find($row['KOD_SUKL']);

		if ($doc === null) {
			$doc = new ProductDocument();
			$doc->setId($row['KOD_SUKL']);
			$this->entityManager->persist($doc);
		}

		$doc->setFileName($row['PIL']);

		try {
			$date = new \DateTime($row['DAT_ROZ_PIL']);
		} catch (\Exception $e) {
			$date = null;
		}
		$doc->setLeafletDecisionDate($date);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}