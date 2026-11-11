<?php

namespace App\Enums;

use App\Enums\Traits\ArrayCases;

enum GroupExamQuestionMatchingEnum: string
{
    use ArrayCases;

    case RIGHT = 'right';
    case LEFT = 'left';
}
