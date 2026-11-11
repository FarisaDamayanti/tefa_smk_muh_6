<?php

namespace App\Enums;

use App\Enums\Traits\ArrayCases;

enum TaskQuestionTypeEnum: string
{
    use ArrayCases;
    
    case MULTIPLE_CHOICE = 'multiple_choice';
    case SINGLE_CHOICE = 'single_choice';
}
