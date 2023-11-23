<?php declare(strict_types=1);

namespace App\Service\File;

use App\Service\SUKL\Exception\SuklException;
use App\Service\Util\LoggerTrait;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;

abstract class LoadCsvFile
{
	use LoggerTrait;

	private const EXTRACT_DIR = '/extracted';
	private const EXTRACT_DIR_TEMP = '/temp';

	protected int $maxHistorySize;

	function __construct(private readonly FileDownloader $fileDownloader) { }

	protected abstract function getStorageFolder(): string;

	protected abstract function getSourceUrl(): string;


	/**
	 * @throws SuklException
	 */
	public function load(int $maxHistorySize = 5): void
	{
		$this->maxHistorySize = $maxHistorySize;
		$this->downloadFile();
		$this->deleteOlderHistory();
	}

	/**
	 * @throws SuklException
	 */
	private function downloadFile(): string
	{
		$url = $this->getSourceUrl();

		// 3.5. Create store dir
		if (!file_exists($this->getStorageFolder().self::EXTRACT_DIR)) {
			mkdir($this->getStorageFolder().self::EXTRACT_DIR, 0777, true);
		}

		// 4. Download file
		$date = (new \DateTime())->format('Y-m-d');
		$path = $this->getStorageFolder().self::EXTRACT_DIR . "/data.csv";
		$result = $this->fileDownloader->downloadFile($url, $path);
		if (!$result) {
			$this->getLogger()->error("Unable to download file from '$url' to '$path'.");
			throw new SuklException("Unable to download file from '$url' to '$path'.");
		}

		return $path;
	}

	private function deleteOlderHistory(): void
	{
		// List all files in directory and sort by name
		$files = scandir($this->getStorageFolder().self::EXTRACT_DIR);

		// Sort by name
		usort($files, function ($a, $b) {
			return strcmp($a, $b);
		});

		// Filter those who are .csv with Y-m-d name
		$files = array_filter($files, function ($file) {
			return preg_match('/^\d{4}-\d{2}-\d{2}\.csv$/', $file);
		});

		// Only keep recent
		$files = array_slice($files, -$this->maxHistorySize);

		$removeCount = count($files) - $this->maxHistorySize;
		if ($removeCount <= 0) {
			$removeCount = count($files) - 1;
		}

		for ($i = 0; $i < $removeCount; $i++) {
			$file = $files[$i];
			$this->getLogger()->info("Removing old file '$file'.");
			unlink($this->getStorageFolder().self::EXTRACT_DIR . "/$file");
		}
	}

}
