<?php
declare(strict_types=1);

namespace App\Service\Filter\MedicalProduct;

enum OrderBy: string
{

	case NAME = 'name';
	case EXPIRATION = 'expiration_hours';
	case ADMINISTRATION_METHOD = 'administration_method';
	case STRENGTH = 'strength';

}