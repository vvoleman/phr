<?php
declare(strict_types=1);


namespace App\Service\UZIS;

use App\Service\SUKL\Exception\SuklException;
use Symfony\Component\DomCrawler\Crawler;

class LoadUZISFile extends \App\Service\File\LoadZipFile
{

	private const URL = 'https://www.uzis.cz/index.php?pg=vystupy--knihovna&id=3371';
	private const SELECTOR = 'li:nth-child(2) > a';
	private const STORAGE_DIR = __DIR__ . '/../../../var/data/uzis';
	private const KEEP_ZIP_FILES = true;

	protected function getLinkWithCrawler(Crawler $crawler): string
	{
		$links = [];
		$crawler->filter(self::SELECTOR)->each(function (Crawler $node) use (&$links) {
			if (str_ends_with($node->attr('href'), '.zip')) {
				$links[] = $node->attr('href');
			}
		});

		if (count($links) === 0) {
			$this->getLogger()->error("No links found on page: " . self::URL);
			throw new SuklException("No links found on page '" . self::URL . "'.");
		}

		$link = $links[0];

		return "https://uzis.cz/$link";
	}

	protected function getStorageFolder(): string
	{
		return self::STORAGE_DIR;
	}

	protected function keepZipFiles(): bool
	{
		return self::KEEP_ZIP_FILES;
	}

	protected function getSourceUrl(): string
	{
		return self::URL;
	}

	protected function isUsingCrawler(): bool
	{
		return true;
	}
}
