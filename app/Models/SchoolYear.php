<?php

namespace App\Models;

use App\Traits\Models\HasManyClassroom;
use App\Traits\Models\HasManyExam;
use App\Traits\Models\HasManyExamQuestion;
use App\Traits\Models\HasManyLessonSchedule;
use App\Traits\Models\HasManySubject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolYear extends Model
{
    use HasFactory, HasUuids, HasManySubject, HasManyLessonSchedule, HasManyClassroom, HasManyExam, HasManyExamQuestion;

    protected $guarded = ['id'];
}
