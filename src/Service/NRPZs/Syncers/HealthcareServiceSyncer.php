<?php declare(strict_types=1);

namespace App\Service\NRPZs\Syncers;

use App\Entity\NRPZS\HealthcareFacility;
use App\Entity\NRPZS\HealthcareService;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class HealthcareServiceSyncer extends AbstractSyncer
{

	private const FILENAME = 'data.csv';

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(HealthcareService::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$id = $row['ZdravotnickeZarizeniId']. '-' . md5($row['OborPece']);
		$service = $repository->find($id);

		if ($service === null) {
			$service = new HealthcareService();
			$service->setId($id);

			$this->entityManager->persist($service);
		}

		// Get facility
		$facility = $this->entityManager->getRepository(HealthcareFacility::class)->find($row['ZdravotnickeZarizeniId']);

		if ($facility === null) {
			$this->logger->error("Facility with ID {$row['ZdravotnickeZarizeniId']} not found");
			return;
		}

		/** @var HealthcareService $service */
		$service->setFacility($facility);
		$service->setCareField($row['OborPece']);
		$service->setCareForm($row['FormaPece']);
		$service->setCareType($row['DruhPece']);
		$service->setCareExtent($row['RozsahPece']);
		$service->setBedCount($row['PocetLuzek']);
		$service->setServiceNote($row['PoznamkaKeSluzbe']);
		$service->setProfessionalRepresentative($row['OdbornyZastupce']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}

//	public static function getDependencies(): array
//	{
//		return [HealthcareFacilitySyncer::class];
//	}
}
