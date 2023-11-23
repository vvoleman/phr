<?php

namespace App\Service\SUKL;

use App\Service\File\LoadZipFile;
use App\Service\SUKL\Exception\SuklException;
use App\Service\Util\LoggerTrait;
use Symfony\Component\DomCrawler\Crawler;

class LoadSUKLFile extends LoadZipFile
{
	use LoggerTrait;

	private const URL = 'https://opendata.sukl.cz/?q=katalog/databaze-lecivych-pripravku-dlp';
	private const SELECTOR = '.field-collection-item-field-soubory a';
	private const STORAGE_DIR = __DIR__ . '/../../../var/data/sukl';
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
		return $links[0];
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
