<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\ProductDocument;
use App\Service\AbstractSyncer;
use App\Service\SUKL\SUKLCsvSyncer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use Psr\Log\LoggerInterface;

class ProductDocumentSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_nazvydokumentu.csv';

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, string $csvPath, string $encoding = 'UTF-8')
    {
        parent::__construct($entityManager, $logger, $csvPath, $encoding);

        SUKLCsvSyncer::$additional[self::class] = [];
    }

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

        $kodSukl = $this->getOrNull($row['KOD_SUKL'], true);
        $pil = $this->getOrNull($row['PIL'], true);
        SUKLCsvSyncer::$additional[self::class][$kodSukl] = $pil;

        return sprintf("
            INSERT INTO product_document
            (id, file_name, leaflet_decision_date)
            values (%s, %s, %s)
            ON DUPLICATE KEY UPDATE file_name = VALUES(file_name), leaflet_decision_date = VALUES(leaflet_decision_date);
        ",
            $kodSukl,
            $pil,
            $this->getOrNull($date->format('Y-m-d'))
        );
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}