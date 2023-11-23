<?php

namespace App\Service\Util;

use ReflectionEnum;

trait Enum {

	public static function tryFromName(string $name): ?static
	{
		$name = strtoupper($name);
		$reflection = new ReflectionEnum(static::class);

		return $reflection->hasCase($name)
			? $reflection->getCase($name)->getValue()
			: null;
	}

}
