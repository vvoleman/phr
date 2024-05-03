<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\MedicalProduct;
use App\Service\AbstractSyncer;
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
	protected function handleRow(array $row, EntityRepository $repository): string
	{
		$sql = sprintf("INSERT INTO medical_product 
            (id, name, strength, packaging, addition, registration_holder, recently_delivered, expiration_hours, form_id, administration_method_id, wrapping_id, country_holder_id, registration_status_id, indication_group_id, dispensing_id, addiction_id, doping_id, document_id ) 
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s ) 
            ON DUPLICATE KEY UPDATE name = VALUES(name), strength = VALUES(strength), packaging = VALUES(packaging), addition = VALUES(addition), registration_holder = VALUES(registration_holder), recently_delivered = VALUES(recently_delivered), expiration_hours = VALUES(expiration_hours), form_id = VALUES(form_id), administration_method_id = VALUES(administration_method_id), wrapping_id = VALUES(wrapping_id), country_holder_id = VALUES(country_holder_id), registration_status_id = VALUES(registration_status_id), indication_group_id = VALUES(indication_group_id), dispensing_id = VALUES(dispensing_id), addiction_id = VALUES(addiction_id), doping_id = VALUES(doping_id), document_id = VALUES(document_id);",
			$this->getOrNull($row['KOD_SUKL'], true),
			$this->getOrNull($row['NAZEV'], true),
			$this->getOrNull($row['SILA'], true),
			$this->getOrNull($row['BALENI'], true),
			$this->getOrNull($row['DOPLNEK'], true),
			$this->getOrNull($row['DRZ'], true),
			$this->getOrNull($row['DODAVKY'] === '1' ? '1' : '0'),
			$this->getOrNull($this->getExpirationHours($row['EXP'], $row['EXP_T'])),
			$this->getOrNull($row['FORMA']),
			$this->getOrNull($row['CESTA']),
			$this->getOrNull($row['OBAL']),
			$this->getOrNull($row['ZEMDRZ']),
			$this->getOrNull($row['REG']),
			$this->getOrNull($row['IS_']),
			$this->getOrNull($row['VYDEJ']),
			$this->getOrNull($row['ZAV']),
			$this->getOrNull($row['DOPING']),
			$this->getOrNull($row['KOD_SUKL'])
		);

		if ($row['LL'] !== null && $row['LL'] !== '') {
			$substanceIds = explode(',', $row['LL'] ?? '');
			foreach ($substanceIds as $substanceId) {
				$sql .= sprintf("
				INSERT INTO medical_product_substance
				(medical_product_id, substance_id)
				VALUES (%s, %s)
				ON DUPLICATE KEY UPDATE medical_product_id = VALUES(medical_product_id), substance_id = VALUES(substance_id);",
					$this->getOrNull($row['KOD_SUKL'], true),
					$this->getOrNull($substanceId)
				);
			}
		}

		return $sql;

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
