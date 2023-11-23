<?php declare(strict_types=1);

namespace App\Service\NRPZs\Syncers;

use App\Service\AbstractSyncer;
use Doctrine\ORM\EntityRepository;

class HealthcareFacilitySyncer extends AbstractSyncer
{

	protected function getRepository(): EntityRepository
	{
		// TODO: Implement getRepository() method.
	}

	/**
	 * @inheritDoc
	 */
	protected function handleRow(array $row, EntityRepository $repository): void
	{
		// TODO: Implement handleRow() method.
	}

	protected function getFilename(): string
	{
		// TODO: Implement getFilename() method.
	}
}
