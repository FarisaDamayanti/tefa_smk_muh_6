<?php

namespace App\Enums;

use App\Enums\Traits\ArrayCases;

enum UserExamStatus: string
{
    use ArrayCases;

    case PASSED = 'passed';
    case NOT_PASSED = 'not_passed';
    case ON_PROGRESS = 'on_progress';
    case GRADING = 'grading';
}
