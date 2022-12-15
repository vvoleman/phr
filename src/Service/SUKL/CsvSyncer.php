<?php

namespace App\Service\SUKL;

use App\Service\SUKL\Syncers\AbstractSyncer;
use App\Service\SUKL\Syncers\AddictionSyncer;
use App\Service\SUKL\Syncers\DependencySorter;
use App\Service\SUKL\Syncers\DopingSyncer;
use App\Service\SUKL\Syncers\MedicalProductSyncer;
use App\Service\SUKL\Syncers\SubstanceSyncer;
use App\Service\Util\LoggerTrait;
use Doctrine\ORM\EntityManagerInterface;

class CsvSyncer
{

	use LoggerTrait;

	public function __construct(private readonly EntityManagerInterface $entityManager) { }

	public function sync(): void
	{
		/** @var array<AbstractSyncer> $syncers */
		$syncers = [
			SubstanceSyncer::class,
			MedicalProductSyncer::class,
		];

		// Sort the syncers by their dependencies. This is necessary because some syncers depend on other syncers
		$sorter = new DependencySorter();
		$sortedSyncers = $sorter->sort($syncers);

		// Now we can sync the data
		$logger = $this->getLogger();
		foreach ($sortedSyncers as $className) {
			$syncer = new $className($this->entityManager, $logger);
			$syncer->sync();
			$this->entityManager->flush();
			$this->entityManager->clear();
		}
	}

}