<?php
declare(strict_types=1);


namespace App\Service\UZIS\Syncers;

use App\Entity\UZIS\DiagnoseGroup;
use Doctrine\ORM\EntityRepository;

class DiagnoseGroupSyncer extends \App\Service\AbstractSyncer
{

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(DiagnoseGroup::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		if ($row['Oddil'] === '') {
			return;
		}

		/** @var DiagnoseGroup|null $diagnoseGroup */
		$diagnoseGroup = $repository->find($row['Kapitola']);

		if ($diagnoseGroup === null) {
			$diagnoseGroup = new DiagnoseGroup();
			$diagnoseGroup->setId($row['Kapitola']);
			$this->entityManager->persist($diagnoseGroup);
		}

		$diagnoseGroup->setName($row['NazevPlny']);
	}

	protected function getFilename(): string
	{
		$year = (new \DateTime())->format('Y');
		return "02_MKN10_${year}_Tabelární ƒást - seznam názvà poloºek_utf8.csv";
	}
}