<?php

namespace App\Models;

use App\Traits\Models\BelongsToClassroomStudent;
use App\Traits\Models\BelongsToEmployee;
use App\Traits\Models\BelongsToRegulation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentViolation extends Model
{
    use HasFactory, HasUuids, BelongsToClassroomStudent, BelongsToRegulation, BelongsToEmployee;

    protected $guarded = ['id'];
}
