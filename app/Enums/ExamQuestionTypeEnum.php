<?php

namespace App\Enums;

use App\Enums\Traits\ArrayCases;
use App\Enums\Traits\EnumToComaSeparated;

enum ExamQuestionTypeEnum: string
{
    use ArrayCases, EnumToComaSeparated;

    case MULTIPLE_CHOICE = 'multiple_choice';
    case SINGLE_CHOICE = 'single_choice';
    case MATCHING = 'matching';
    case ESSAY = 'essay';
}
