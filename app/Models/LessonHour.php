<?php

namespace App\Models;

use App\Traits\Models\BelongsToSchool;
use App\Traits\Models\HasManyAttendanceJournal;
use App\Traits\Models\HasManyLessonSchedule;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonHour extends Model
{
    use HasFactory, HasManyLessonSchedule, HasManyAttendanceJournal;

    protected $guarded = ['id'];
}
