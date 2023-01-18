<?php

namespace App\Service;

use App\Service\SUKL\Exception\SuklException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;

abstract class AbstractSyncer
{

	public function __construct(
		protected readonly EntityManagerInterface $entityManager,
		protected readonly LoggerInterface $logger,
		protected readonly string $csvPath,
	)
	{
	}

	/**
	 * @return array<class-string>
	 */
	public static function getDependencies(): array
	{
		return [];
	}

	public function hasHeader(): bool
	{
		return true;
	}

	protected abstract function getRepository(): EntityRepository;

	/**
	 * @param array $row
	 */
	protected abstract function handleRow(array $row, EntityRepository $repository): void;

	public function sync(): void
	{
		$syncerName = static::class;
		$this->logger->info("$syncerName started");
		$startTimestamp = microtime(true);

		$data = $this->getFileData();
		$repository = $this->getRepository();

		$i = 0;
		$maxRecords = 5000;
		foreach ($data as $item) {
			$this->handleRow($item, $repository);
			$i++;

			if ($i % $maxRecords === 0) {
				$memory = memory_get_usage();
				$this->logger->info("$syncerName: $i records processed, memory: $memory");

				$this->entityManager->flush();
				$this->entityManager->clear();
			}
		}

		$this->entityManager->flush();
		$this->entityManager->clear();
		$memory = memory_get_usage();
		$this->logger->info(
			"$syncerName finished. Memory: $memory. Processed in " . (microtime(true) - $startTimestamp) . 's'
		);
	}

	protected function getEntity(string $entityClass, mixed $id): object|null
	{
		return $this->entityManager->find($entityClass, $id);
	}

	protected abstract function getFilename(): string;

	/**
	 * @throws SuklException
	 */
	protected function getFileData(): iterable
	{
		$filePath = $this->csvPath . '/' . $this->getFilename();

		if (!file_exists($filePath)) {
			$syncer = static::class;
			$this->logger->error("File '$filePath' not found. File is required to run Syncer $syncer");
			throw new SuklException("File $filePath does not exist.");
		}

		$csv = Reader::createFromPath($filePath);

		//convert from windows 1250 to utf8
		try {
			$csv->addStreamFilter('convert.iconv.windows-1250/utf-8');

			$csv->setHeaderOffset(0);
			$csv->setDelimiter(';');
		} catch (\Exception $e) {
			$this->logger->error($e->getMessage());
			throw new SuklException($e->getMessage());
		}

		$records = $csv->getRecords(); //returns all the CSV records as an Iterator object

		foreach ($records as $record) {
			yield $record;
		}
	}

}