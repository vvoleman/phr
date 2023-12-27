<?php
declare(strict_types=1);


namespace App\Service\UZIS;

use App\Service\UZIS\Syncers\DiagnoseSyncer;

class UZISCsvSyncer extends \App\Service\CsvSyncer
{

	protected const CSV_PATH = __DIR__ . '/../../../var/data/uzis/extracted/mkn10_strukturovane_podklady_*/cp_utf8';

	/**
	 * @inheritDoc
	 */
	protected function getSyncers(): array
	{
		return [DiagnoseSyncer::class];
	}

	protected function getEncoding(): string
	{
		return 'utf-8';
	}

	protected function getCsvPath(): string
	{
		$year = (new \DateTime())->format('Y');
		return sprintf(self::CSV_PATH, $year);
	}
}
