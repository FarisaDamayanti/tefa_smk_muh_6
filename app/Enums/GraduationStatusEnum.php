<?php

namespace App\Enums;

use App\Enums\Traits\ArrayCases;
use App\Enums\Traits\EnumToComaSeparated;

enum GraduationStatusEnum: string
{
    use ArrayCases, EnumToComaSeparated;

    case PASSED = 'passed';
    case NOT_PASSED = 'not_passed';
}
