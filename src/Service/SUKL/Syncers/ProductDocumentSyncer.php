<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\ProductDocument;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;
use Exception;

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
	protected function handleRow(array $row, EntityRepository $repository): string
	{
        try {
            $date = new \DateTime($row['DAT_ROZ_PIL']);
        } catch (Exception) {
            $date = null;
        }

        return sprintf("
            INSERT INTO product_document
            (id, file_name, leaflet_decision_date)
            values (%s, %s, %s)
            ON DUPLICATE KEY UPDATE file_name = VALUES(file_name), leaflet_decision_date = VALUES(leaflet_decision_date);
        ",
            $this->getOrNull($row['KOD_SUKL'], true),
            $this->getOrNull($row['PIL'], true),
            $this->getOrNull($date->format('Y-m-d'))
        );
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}