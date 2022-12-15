<?php

namespace App\Service\SUKL;

use App\Service\File\FileDownloader;
use App\Service\SUKL\Exception\SuklException;
use App\Service\Util\LoggerTrait;
use Symfony\Component\DomCrawler\Crawler;

class LoadZipFile
{
	use LoggerTrait;

	private const URL = 'https://opendata.sukl.cz/?q=katalog/databaze-lecivych-pripravku-dlp';
	private const SELECTOR = '.field-collection-item-field-soubory a';
	private const STORE_ZIP_DIR = __DIR__ . '/../../../var/data/sukl/zips';
	private const EXTRACT_DIR = __DIR__ . '/../../../var/data/sukl/extracted';
	private const EXTRACT_DIR_TEMP = __DIR__ . '/../../../var/data/sukl/temp';
	private const KEEP_ZIP_FILES = true;

	function __construct(private readonly FileDownloader $fileDownloader) { }

	/**
	 * @throws SuklException
	 */
	public function load(): void
	{
		$zipPath = $this->downloadZipFile();

		$this->extractZipFile($zipPath);
	}

	/**
	 * @throws SuklException
	 */
	private function downloadZipFile(): string
	{
		// 1. Get html content of URL
		try {
			$html = file_get_contents(self::URL);
		} catch (\Exception $e) {
			throw new SuklException("Unable to access URL '" . self::URL . "'.");
		}

		if ($html === false) {
			$this->getLogger()->error("Unable to load content from URL: " . self::URL);
			throw new SuklException("Unable to load content from URL '" . self::URL . "'.");
		}

		// 2. Parse html content
		$crawler = new Crawler($html);

		// 3. Get links
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

		// 3.5. Create store dir
		if (!file_exists(self::STORE_ZIP_DIR)) {
			mkdir(self::STORE_ZIP_DIR, 0777, true);
		}

		// 4. Download file
		$date = (new \DateTime())->format('Y-m-d');
		$path = self::STORE_ZIP_DIR . "/sukl_$date.zip";
		$result = $this->fileDownloader->downloadFile($link, $path);

		if (!$result) {
			$this->getLogger()->error("Unable to download file from '$link' to '$path'.");
			throw new SuklException("Unable to download file from '$link' to '$path'.");
		}

		return $path;
	}

	/**
	 * @throws SuklException
	 */
	private function extractZipFile(string $zipPath): void
	{
		// 1. Check if temp dir exists, delete it if yes. After that create it.
		if (file_exists(self::EXTRACT_DIR_TEMP)) {
			$this->getLogger()->info("Deleting temp dir: " . self::EXTRACT_DIR_TEMP);
			try {
				$this->removeDir(self::EXTRACT_DIR_TEMP);
			} catch (\Exception $e) {
				$this->getLogger()->error("Unable to delete temp dir: " . self::EXTRACT_DIR_TEMP);
				throw new SuklException("Unable to delete temp dir: " . self::EXTRACT_DIR_TEMP);
			}
		}

		$this->getLogger()->info("Creating temp dir: " . self::EXTRACT_DIR_TEMP);

		try {
			mkdir(self::EXTRACT_DIR_TEMP, 0777, true);
		} catch (\Exception $e) {
			$this->getLogger()->error("Unable to create temp dir: " . self::EXTRACT_DIR_TEMP);
			throw new SuklException("Unable to create temp dir: " . self::EXTRACT_DIR_TEMP);
		}

		// 2. Extract zip file to temp dir
		$zip = new \ZipArchive();
		$status = $zip->open($zipPath);

		if ($status !== true) {
			$this->getLogger()->error("Unable to open zip file: " . $zipPath . " (status: $status)");
			throw new SuklException("Unable to open zip file: " . $zipPath . " (status: $status)");
		}

		$status = $zip->extractTo(self::EXTRACT_DIR_TEMP);

		if ($status !== true) {
			$this->getLogger()->error("Unable to extract zip file: " . $zipPath);
			throw new SuklException("Unable to extract zip file: " . $zipPath);
		}

		$this->getLogger()->info("Zip file extracted to: " . self::EXTRACT_DIR_TEMP);
		$zip->close();

		// 3. Remove old extract dir
		if (file_exists(self::EXTRACT_DIR)) {
			$this->getLogger()->info("Deleting old extract dir: " . self::EXTRACT_DIR);
			try {
				$this->removeDir(self::EXTRACT_DIR);
			} catch (\Exception $e) {
				$this->getLogger()->error("Unable to delete old extract dir: " . self::EXTRACT_DIR, [
					'exception' => $e,
				]);
				throw new SuklException("Unable to delete old extract dir: " . self::EXTRACT_DIR);
			}
		}

		// 4. Rename temp dir to extract dir
		$this->getLogger()->info(
			"Renaming temp dir to extract dir: " . self::EXTRACT_DIR_TEMP . " -> " . self::EXTRACT_DIR
		);
		try {
			rename(self::EXTRACT_DIR_TEMP, self::EXTRACT_DIR);
		} catch (\Exception $e) {
			$this->getLogger()->error(
				"Unable to rename temp dir to extract dir: " . self::EXTRACT_DIR_TEMP . " -> " . self::EXTRACT_DIR
			);
			throw new SuklException(
				"Unable to rename temp dir to extract dir: " . self::EXTRACT_DIR_TEMP . " -> " . self::EXTRACT_DIR
			);
		}

		if (self::KEEP_ZIP_FILES === false) {
			$this->getLogger()->info("Deleting zip file: " . $zipPath);
			try {
				unlink($zipPath);
			} catch (\Exception $e) {
				$this->getLogger()->error("Unable to delete zip file: " . $zipPath);
				throw new SuklException("Unable to delete zip file: " . $zipPath);
			}
		}
	}

	private function removeDir(string $dir): void
	{
		// remove dir containing files
		$files = glob($dir . '/*');
		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
		rmDir($dir);
	}

}
