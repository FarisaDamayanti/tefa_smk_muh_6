<?php

namespace App\Models;

use App\Traits\Models\BelongsToClassroom;
use App\Traits\Models\BelongsToEmployee;
use App\Traits\Models\BelongsToSchoolYear;
use App\Traits\Models\BelongsToSubject;
use App\Traits\Models\HasManyModules;
use App\Traits\Models\HasManyTask;
use App\Traits\Models\HasManyTeacherJournal;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToEmployee,
        BelongsToSubject,
        BelongsToClassroom,
        BelongsToSchoolYear,
        HasManyModules,
        HasManyTask,
        HasManyTeacherJournal,
        SoftDeletes;

    protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'description',
        'topic_number',
        'employee_id',
        'subject_id',
        'classroom_id',
        'school_year_id',
        'semester',
    ];

}
