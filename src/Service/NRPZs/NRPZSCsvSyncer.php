<?php declare(strict_types=1);

namespace App\Service\NRPZs;

use App\Service\CsvSyncer;
use App\Service\NRPZs\Syncers\HealthcareFacilitySyncer;
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
			HealthcareFacilitySyncer::class,
			HealthcareServiceSyncer::class,
		];
	}

	protected function getEncoding(): string
	{
		return 'windows-1250';
	}

	protected function getCsvPath(): string
	{
		return self::CSV_PATH;
	}
}
