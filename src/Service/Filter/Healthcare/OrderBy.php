<?php

namespace App\Service\Filter\Healthcare;

enum OrderBy : string
{
	case NAME = 'name';
	case ID = 'id';
	case CITY = 'city';
	case STREET = 'street';
	case ACTIVITY_STARTED_AT = 'activity_started_at';
}
