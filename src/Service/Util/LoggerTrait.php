<?php

namespace App\Service\Util;

use Psr\Log\LoggerInterface;

trait LoggerTrait
{

	private ?LoggerInterface $logger = null;

	/**
	 * @required
	 */
	public function setLogger(LoggerInterface $logger): void
	{
		$this->logger = $logger;
	}

	public function getLogger(): LoggerInterface
	{
		return $this->logger;
	}

}