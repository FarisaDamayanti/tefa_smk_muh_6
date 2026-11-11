<?php

namespace App\Enums;

use App\Enums\Traits\ArrayCases;
use App\Enums\Traits\EnumToComaSeparated;

enum AnswerOptionEnum: string
{
    use EnumToComaSeparated, ArrayCases;
    
    case OPTION_A = 'option_a';
    case OPTION_B = 'option_b';
    case OPTION_C = 'option_c';
    case OPTION_D = 'option_d';
    case OPTION_E = 'option_e';
}