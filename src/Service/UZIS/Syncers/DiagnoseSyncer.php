<?php
declare(strict_types=1);

namespace App\Service\UZIS\Syncers;

use App\Entity\UZIS\Diagnose;
use App\Entity\UZIS\DiagnoseGroup;
use Doctrine\ORM\EntityRepository;

class DiagnoseSyncer extends \App\Service\AbstractSyncer
{

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(Diagnose::class);
	}

	public static function getDependencies(): array
	{
		return [
			DiagnoseGroupSyncer::class,
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		if ($row['KodSTeckou'] === '') {
			return;
		}

		/** @var Diagnose|null $diagnose */
		$diagnose = $repository->find($row['Kod']);

		if ($diagnose === null) {
			$diagnose = new Diagnose();
			$diagnose->setId($row['Kod']);
			$this->entityManager->persist($diagnose);
		}

		$diagnose->setName($row['NazevPlny']);
		$diagnose->setParent($this->entityManager->getReference(DiagnoseGroup::class, $row['Kapitola']));
	}

	protected function getFilename(): string
	{
		$year = (new \DateTime())->format('Y');
		return "02_MKN10_${year}_Tabelární ƒást - seznam názvà poloºek_utf8.csv";
	}
}