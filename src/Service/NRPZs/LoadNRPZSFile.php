<?php declare(strict_types=1);

namespace App\Service\NRPZs;

use App\Service\File\LoadCsvFile;
use Symfony\Component\DomCrawler\Crawler;

class LoadNRPZSFile extends LoadCsvFile
{

	protected function getLinkWithCrawler(Crawler $crawler): string
	{
		// TODO: Implement getLinkWithCrawler() method.
	}

	protected function getStorageFolder(): string
	{
		// TODO: Implement getStorageFolder() method.
	}

	protected function keepZipFiles(): bool
	{
		// TODO: Implement keepZipFiles() method.
	}

	protected function getSourceUrl(): string
	{
		// TODO: Implement getSourceUrl() method.
	}

	protected function isUsingCrawler(): bool
	{
		// TODO: Implement isUsingCrawler() method.
	}
}
