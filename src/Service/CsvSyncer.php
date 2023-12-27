<?php
declare(strict_types=1);


namespace App\Service;

use App\Service\Util\LoggerTrait;
use Doctrine\ORM\EntityManagerInterface;

abstract class CsvSyncer
{

	use LoggerTrait;

	public function __construct(private readonly EntityManagerInterface $entityManager) { }

	/**
	 * @return array<AbstractSyncer>
	 */
	protected abstract function getSyncers(): array;

	protected abstract function getCsvPath(): string;

	protected abstract function getEncoding(): string;

	public function sync(): void
	{
		/** @var array<AbstractSyncer> $syncers */
		$syncers = 	$this->getSyncers();

		// Sort the syncers by their dependencies. This is necessary because some syncers depend on other syncers
		$sorter = new DependencySorter();
		$sortedSyncers = $sorter->sort($syncers);

		// Now we can sync the data
		$logger = $this->getLogger();
		foreach ($sortedSyncers as $className) {
			$syncer = new $className($this->entityManager, $logger, $this->getCsvPath(), $this->getEncoding());
			$syncer->sync();
			$this->entityManager->flush();
			$this->entityManager->clear();
		}
	}

}
