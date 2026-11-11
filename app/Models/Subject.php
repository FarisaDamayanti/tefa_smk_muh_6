<?php

namespace App\Models;

use App\Traits\Models\BelongsToReligion;
use App\Traits\Models\HasManyExam;
use App\Traits\Models\HasManyExamQuestion;
use App\Traits\Models\HasManyTask;
use App\Traits\Models\HasManyTeacherSubject;
use App\Traits\Models\HasManyTopic;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory,
        HasUuids,
        HasManyTeacherSubject,
        BelongsToReligion,
        HasManyTask,
        HasManyExam,
        HasManyTopic,
        HasManyExamQuestion;

    protected $guarded = ['id'];
}
