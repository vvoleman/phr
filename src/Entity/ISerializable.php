<?php
declare(strict_types=1);


namespace App\Entity;

interface ISerializable
{

	public function serialize(): array;

}