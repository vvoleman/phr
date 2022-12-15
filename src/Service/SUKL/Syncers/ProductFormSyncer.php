<?php

namespace App\Service\SUKL\Syncers;

use App\Entity\ProductForm;
use Doctrine\ORM\EntityRepository;

class ProductFormSyncer extends AbstractSyncer
{

	private const FILENAME = 'dlp_formy.csv';

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
		$form->setEdqmCode($row['KOD_EDQM']);
	}

	protected function getFilename(): string
	{
		return self::FILENAME;
	}
}