<?php
declare(strict_types=1);


namespace App\Service\File;

use App\Service\SUKL\Exception\SuklException;
use App\Service\Util\LoggerTrait;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;

abstract class LoadZipFile
{
	use LoggerTrait;

	private const STORE_ZIP_DIR = '/zips';
	private const EXTRACT_DIR = '/extracted';
	private const EXTRACT_DIR_TEMP = '/temp';

	function __construct(private readonly FileDownloader $fileDownloader) { }

	protected abstract function getLinkWithCrawler(Crawler $crawler): string;

	protected abstract function getStorageFolder(): string;

	protected abstract function keepZipFiles(): bool;

	protected abstract function getSourceUrl(): string;

	protected abstract function isUsingCrawler(): bool;

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
		$url = $this->getSourceUrl();

		if ($this->isUsingCrawler()) {
			$url = $this->crawlSourceLink($url);
		}

		// 3.5. Create store dir
		if (!file_exists($this->getStorageFolder().self::STORE_ZIP_DIR)) {
			mkdir($this->getStorageFolder().self::STORE_ZIP_DIR, 0777, true);
		}

		// 4. Download file
		$date = (new \DateTime())->format('Y-m-d');
		$path = $this->getStorageFolder().self::STORE_ZIP_DIR . "/$date.zip";
		$result = $this->fileDownloader->downloadFile($url, $path);
		if (!$result) {
			$this->getLogger()->error("Unable to download file from '$url' to '$path'.");
			throw new SuklException("Unable to download file from '$url' to '$path'.");
		}

		return $path;
	}

	/**
	 * @throws SuklException
	 */
	private function crawlSourceLink(string $url): string {
		// 1. Get html content of URL
		try {
			$html = file_get_contents($url);
		} catch (\Exception $e) {
			throw new SuklException("Unable to access URL '" . $url . "'.");
		}

		if ($html === false) {
			$this->getLogger()->error("Unable to load content from URL: " . $url);
			throw new SuklException("Unable to load content from URL '" . $url . "'.");
		}

		// 2. Parse html content
		$crawler = new Crawler($html);

		return $this->getLinkWithCrawler($crawler);
	}

	/**
	 * @throws SuklException
	 */
	private function extractZipFile(string $zipPath): void
	{
		$extractDirTemp = $this->getStorageFolder().self::EXTRACT_DIR_TEMP;
		// 1. Check if temp dir exists, delete it if yes. After that create it.
		if (file_exists($extractDirTemp)) {
			$this->getLogger()->info("Deleting temp dir: " . $extractDirTemp);
			try {
				$this->removeDir($extractDirTemp);
			} catch (\Exception $e) {
				$this->getLogger()->error("Unable to delete temp dir: " . $extractDirTemp, ['exception' => $e->getMessage()]);
				throw new SuklException("Unable to delete temp dir: " . $extractDirTemp);
			}
		}

		$this->getLogger()->info("Creating temp dir: " . $extractDirTemp);

		try {
			mkdir($extractDirTemp, 0777, true);
		} catch (\Exception $e) {
			$this->getLogger()->error("Unable to create temp dir: " . $extractDirTemp);
			throw new SuklException("Unable to create temp dir: " . $extractDirTemp);
		}

		// 2. Extract zip file to temp dir
		$zip = new \ZipArchive();
		$status = $zip->open($zipPath);

		if ($status !== true) {
			$this->getLogger()->error("Unable to open zip file: " . $zipPath . " (status: $status)");
			throw new SuklException("Unable to open zip file: " . $zipPath . " (status: $status)");
		}

		$status = $zip->extractTo($extractDirTemp);

		if ($status !== true) {
			$this->getLogger()->error("Unable to extract zip file: " . $zipPath);
			throw new SuklException("Unable to extract zip file: " . $zipPath);
		}

		$this->getLogger()->info("Zip file extracted to: " . $extractDirTemp);
		$zip->close();

		// 3. Remove old extract dir
		$extractDir = $this->getStorageFolder().self::EXTRACT_DIR;
		if (file_exists($extractDir)) {
			$this->getLogger()->info("Deleting old extract dir: " . $extractDir);
			try {
				$this->removeDir($extractDir);
			} catch (\Exception $e) {
				$this->getLogger()->error("Unable to delete old extract dir: " . $extractDir, [
					'exception' => $e->getMessage(),
				]);
				throw new SuklException("Unable to delete old extract dir: " . $extractDir);
			}
		}

		// 4. Rename temp dir to extract dir
		$this->getLogger()->info(
			"Renaming temp dir to extract dir: " . $extractDirTemp . " -> " . $extractDir
		);
		try {
			rename($extractDirTemp, $extractDir);
		} catch (\Exception $e) {
			$this->getLogger()->error(
				"Unable to rename temp dir to extract dir: " . $extractDirTemp . " -> " . $extractDir
			);
			throw new SuklException(
				"Unable to rename temp dir to extract dir: " . $extractDirTemp . " -> " . $extractDir
			);
		}

		if ($this->keepZipFiles() === false) {
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
		$fs = new Filesystem();
		$fs->remove($dir);
	}
}
