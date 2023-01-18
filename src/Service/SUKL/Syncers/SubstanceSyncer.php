<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\Addiction;
use App\Entity\Doping;
use App\Entity\Source;
use App\Entity\Substance;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class SubstanceSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_latky.csv';

	public static function getDependencies(): array
	{
		return [
			AddictionSyncer::class,
			DopingSyncer::class,
			SourceSyncer::class,
		];
	}

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(Substance::class);
	}

	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$id = $row['KOD_LATKY'];

		if ((int) $id < 1) {
			return;
		}

		$substance = $repository->find($row['KOD_LATKY']);

		if ($substance === null) {
			$substance = new Substance();
			$substance->setId($row['KOD_LATKY']);
			$this->entityManager->persist($substance);
		}

		$source = $this->getEntity(Source::class, $row['ZDROJ']);
		if ($source !== null) {
			/** @var Source $source */
			$substance->setSource($source);
		}

		$addiction = $this->getEntity(Addiction::class, $row['ZAV']);
		if ($addiction !== null) {
			/** @var Addiction $addiction */
			$substance->setAddiction($addiction);
		}

		$doping = $this->getEntity(Doping::class, $row['DOP']);
		if ($doping !== null) {
			/** @var Doping $doping */
			$substance->setDoping($doping);
		}

		$substance->setName($row['NAZEV']);
		$substance->setNameEn($row['NAZEV_EN']);
		$substance->setNameInn($row['NAZEV_INN']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}