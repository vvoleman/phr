<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\ProductForm;
use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class ProductFormSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_formy.csv';

	private static array $CACHED_FORMS = [];

	protected function getRepository(): EntityRepository
	{
		return $this->entityManager->getRepository(ProductForm::class);
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		$form = $repository->find($row['FORMA']);

		if ($form === null) {
			$form = new ProductForm();
			$form->setId($row['FORMA']);
			$this->entityManager->persist($form);
		}

		$form->setName($row['NAZEV']);
		$form->setNameEn($row['NAZEV_EN']);
		$form->setNameLat($row['NAZEV_LAT']);
		$form->setIsCannabis($row['JE_KONOPI'] === 'A');
		$form->setEdqmCode($row['EDQM_KOD']);
		$form->setShortName($this->getShortenName($row['FORMA']));
		$this->entityManager->persist($form);
	}

	private function getShortenName(string $id): string|null {
		$path = __DIR__ . '/../../../../var/data/list/product_form_names.json';
		$file = file_get_contents($path);
		$names = json_decode($file, true);

		if (isset(self::$CACHED_FORMS[$id])) {
			return self::$CACHED_FORMS[$id];
		}

		foreach ($names as $key => $name) {
			if (str_contains($id, $key)) {
				self::$CACHED_FORMS[$id] = $name;
				return $name;
			}
		}

		return $names[$id] ?? null;
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}
