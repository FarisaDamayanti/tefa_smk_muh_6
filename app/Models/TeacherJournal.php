<?php

namespace App\Models;

use App\Traits\Models\BelongsToLessonSchedule;
use App\Traits\Models\BelongsToTopic;
use App\Traits\Models\HasManyAttendanceJournal;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherJournal extends Model
{
    use HasFactory, HasUuids, BelongsToLessonSchedule, BelongsToTopic, HasManyAttendanceJournal;

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
    ];
}
