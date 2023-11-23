<?php

namespace App\Service\Filter\Healthcare;

use App\Service\Util\Enum;

enum SearchBy: string
{
	use Enum;

	case FULL_NAME = 'hf.fullName';
	case ID = 'hf.id';
	case CITY = 'hf.city';
	case REGION = 'hf.region';
	case REGION_CODE = 'hf.regionCode';
	case REPRESENTATIVE = 'hs.professionalRepresentative';
	case TELEPHONE = 'hf.providerTelephone';
	case FAX = 'hf.providerFax';
	case EMAIL = 'hf.providerEmail';
	case WEB = 'hf.providerWeb';
	case TYPE = 'hf.providerType';
	case CARE_FIELD = 'hs.careField';
	case ANY = 'any';
}
