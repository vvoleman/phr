<?php declare(strict_types=1);

namespace App\Service\NRPZs;

use App\Service\File\LoadCsvFile;
use Symfony\Component\DomCrawler\Crawler;

class LoadNRPZSFile extends LoadCsvFile
{

	private const URL = 'https://nrpzs.uzis.cz/res/file/export/export-sluzby-%s-%s.csv';
	private const STORAGE_DIR = __DIR__ . '/../../../var/data/nrpzs';


	protected function getStorageFolder(): string
	{
		return self::STORAGE_DIR;
	}

	protected function getSourceUrl(): string
	{
		$today = new \DateTimeImmutable();
		$year = $today->format('Y');
		$month = $today->format('m');

		return sprintf(self::URL, $year, $month);
	}
}
