<?php

namespace App\Service\SUKL;

use App\Service\CsvSyncer;
use App\Service\SUKL\Syncers\MedicalProductSyncer;
use App\Service\SUKL\Syncers\SubstanceSyncer;

class SUKLCsvSyncer extends CsvSyncer
{

	protected const CSV_PATH = __DIR__ . '/../../../var/data/sukl';

	protected function getSyncers(): array
	{
		return [
			SubstanceSyncer::class,
			MedicalProductSyncer::class,
		];
	}

	protected function getCsvPath(): string
	{
		return self::CSV_PATH;
	}
}