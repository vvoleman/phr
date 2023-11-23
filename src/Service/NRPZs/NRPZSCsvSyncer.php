<?php declare(strict_types=1);

namespace App\Service\NRPZs;

use App\Service\CsvSyncer;
use App\Service\NRPZs\Syncers\HealthcareServiceSyncer;

class NRPZSCsvSyncer extends CsvSyncer
{

	protected const CSV_PATH = __DIR__ . '/../../../var/data/nrpzs/extracted';

	/**
	 * @inheritDoc
	 */
	protected function getSyncers(): array
	{
		return [
			HealthcareServiceSyncer::class,
		];
	}

	protected function getCsvPath(): string
	{
		return self::CSV_PATH;
	}
}
