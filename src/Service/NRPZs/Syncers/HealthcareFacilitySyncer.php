<?php declare(strict_types=1);

namespace App\Service\NRPZs\Syncers;

use App\Entity\NRPZS\HealthcareFacility;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class HealthcareFacilitySyncer extends AbstractSyncer
{

	private const FILENAME = 'data.csv';

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(HealthcareFacility::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$facility = $repository->find($row['ZdravotnickeZarizeniId']);

		if ($facility === null) {
			$facility = new HealthcareFacility();
			$facility->setId($row['ZdravotnickeZarizeniId']);

			$this->entityManager->persist($facility);
		}

		/** @var HealthcareFacility $facility */

		$facility->setId($row['ZdravotnickeZarizeniId']);
		$facility->setFullName($row['NazevCely']);
		$facility->setFacilityCode($row['ZdravotnickeZarizeniKod']);
		$facility->setFacilityTypeCode($row['DruhZarizeniKod']);
		$facility->setFacilityType($row['DruhZarizeni']);
		$facility->setSecondaryFacilityType($row['DruhZarizeniSekundarni']);
		$facility->setFounder($row['Zrizovatel']);
		$facility->setCity($row['Obec']);
		$facility->setPostalCode($row['Psc']);
		$facility->setStreet($row['Ulice']);
		$facility->setHouseNumberOrientation($row['CisloDomovniOrientacni']);
		$facility->setRegion($row['Kraj']);
		$facility->setRegionCode($row['KrajKod']);
		$facility->setDistrict($row['Okres']);
		$facility->setDistrictCode($row['OkresKod']);
		$facility->setAdministrativeDistrict($row['SpravniObvod']);
		$facility->setProviderTelephone($row['PoskytovatelTelefon']);
		$facility->setProviderFax($row['PoskytovatelFax']);

		$startedAt = empty($row['DatumZahajeniCinnosti']) ? null : new \DateTime($row['DatumZahajeniCinnosti']);
		$facility->setActivityStartedAt($startedAt);

		$facility->setProviderEmail($row['PoskytovatelEmail']);
		$facility->setProviderWeb($row['PoskytovatelWeb']);
		$facility->setProviderType($row['DruhPoskytovatele']);
		$facility->setProviderName($row['PoskytovatelNazev']);
		$facility->setIdentificationNumber($row['Ico']);
		$facility->setPersonType($row['TypOsoby']);
		$facility->setRegionCodeOfDomicile($row['KrajKodSidlo']);
		$facility->setDomicileRegion($row['KrajSidlo']);
		$facility->setDistrictCodeOfDomicile($row['OkresKodSidlo']);
		$facility->setDomicileDistrict($row['OkresSidlo']);
		$facility->setPostalCodeOfDomicile($row['PscSidlo']);
		$facility->setDomicileCity($row['ObecSidlo']);
		$facility->setDomicileStreet($row['UliceSidlo']);
		$facility->setDomicileHouseNumberOrientation($row['CisloDomovniOrientacniSidlo']);
		$facility->setGps($row['GPS']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}
