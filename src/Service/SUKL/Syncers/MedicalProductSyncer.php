<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\AdministrationMethod;
use App\Entity\Country;
use App\Entity\Dispensing;
use App\Entity\Doping;
use App\Entity\IndicationGroup;
use App\Entity\MedicalProduct;
use App\Entity\ProductDocument;
use App\Entity\ProductForm;
use App\Entity\RegistrationStatus;
use App\Entity\Substance;
use App\Entity\Wrapping;
use Doctrine\ORM\EntityRepository;

class MedicalProductSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_lecivepripravky.csv';

	public static function getDependencies(): array
	{
		return [
			AdministrationMethodSyncer::class,
			CountrySyncer::class,
			DispensingSyncer::class,
			DopingSyncer::class,
			IndicationGroupSyncer::class,
			ProductDocumentSyncer::class,
			ProductFormSyncer::class,
			RegistrationStatusSyncer::class,
			WrappingSyncer::class,
		];
	}


	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(MedicalProduct::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$medicalProduct = $repository->find($row['KOD_SUKL']);

		if ($medicalProduct === null) {
			$medicalProduct = new MedicalProduct();
			$medicalProduct->setId($row['KOD_SUKL']);
			$this->entityManager->persist($medicalProduct);
		}

		$medicalProduct->setName($row['NAZEV']);
		$medicalProduct->setStrength($row['SILA']);
		$medicalProduct->setPackaging($row['BALENI']);
		$medicalProduct->setAddition($row['DOPLNEK']);
		$medicalProduct->setRegistrationHolder($row['DRZ']);
		$medicalProduct->setRecentlyDelivered($row['DODAVKY'] === '1');
		$medicalProduct->setExpirationHours($this->getExpirationHours($row['EXP'], $row['EXP_T']));

		$doping = $this->getEntity(Doping::class, $row['DOPING']);
		if ($doping !== null) {
			/** @var Doping $doping */
			$medicalProduct->setDoping($doping);
		}

		$status = $this->getEntity(RegistrationStatus::class, $row['REG']);
		if ($status !== null) {
			/** @var RegistrationStatus $status */
			$medicalProduct->setRegistrationStatus($status);
		}

		$wrapping = $this->getEntity(Wrapping::class, $row['OBAL']);
		if ($wrapping !== null) {
			/** @var Wrapping $wrapping */
			$medicalProduct->setWrapping($wrapping);
		}

		$administrationMethod = $this->getEntity(AdministrationMethod::class, $row['CESTA']);
		if ($administrationMethod !== null) {
			/** @var AdministrationMethod $administrationMethod */
			$medicalProduct->setAdministrationMethod($administrationMethod);
		}

		$productForm = $this->getEntity(ProductForm::class, $row['FORMA']);
		if ($productForm !== null) {
			/** @var ProductForm $productForm */
			$medicalProduct->setForm($productForm);
		}

		$country = $this->getEntity(Country::class, $row['ZEMDRZ']);
		if ($country !== null) {
			/** @var Country $country */
			$medicalProduct->setCountryHolder($country);
		}

		$indicationGroup = $this->getEntity(IndicationGroup::class, $row['IS_']);
		if ($indicationGroup !== null) {
			/** @var IndicationGroup $indicationGroup */
			$medicalProduct->setIndicationGroup($indicationGroup);
		}

		$dispensing = $this->getEntity(Dispensing::class, $row['VYDEJ']);
		if ($dispensing !== null) {
			/** @var Dispensing $dispensing */
			$medicalProduct->setDispensing($dispensing);
		}

		$document = $this->getEntity(ProductDocument::class, $row['KOD_SUKL']);
		if ($document !== null) {
			/** @var ProductDocument $document */
			$medicalProduct->setDocument($document);
		} else {
//			$this->logger->warning(sprintf('Document for medical product %s not found', $row['KOD_SUKL']));
		}


		if ($row['LL'] !== null && $row['LL'] !== '') {
			$substanceIds = explode(',', $row['LL'] ?? '');
			foreach ($substanceIds as $substanceId) {
				$substance = $this->getEntity(Substance::class, $substanceId);
				if ($substance !== null) {
					/** @var Substance $substance */
					$medicalProduct->addSubstance($substance);
				}
			}
		}

	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}

	private function getExpirationHours(string $value, string $type): int
	{
		$value = (int)$value;
		$multipliers = [
			'H' => 1,
			'D' => 24,
			'W' => 24 * 7,
			'M' => 24 * 30,
		];

		return $value * ($multipliers[$type] ?? 1);
	}
}