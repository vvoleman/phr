<?php

namespace App\Service\Filter\Healthcare;

enum OrderBy : string
{
	case NAME = 'fullName';
	case ID = 'id';
	case CITY = 'city';
	case STREET = 'street';
	case ACTIVITY_STARTED_AT = 'activityStartedAt';
}
