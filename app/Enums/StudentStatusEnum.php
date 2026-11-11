<?php

namespace App\Enums;

use App\Enums\Traits\ArrayCases;

enum StudentStatusEnum: string
{
    use ArrayCases;

    case ACTIVE = 'active';
    case PASSED = 'passed';
    case KICK = 'kick';
}
