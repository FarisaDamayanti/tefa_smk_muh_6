<?php

namespace App\Enums;

enum StatusSubmissionTaskEnum: string
{
    case SUBMITTED = 'submitted';
    case OVERDUE = 'overdue';
}
