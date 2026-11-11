<?php

namespace App\Models;

use App\Enums\GenderEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Models\HasManyExtracurricular;
use App\Traits\Models\HasManyTeacherSubject;
use App\Traits\Models\BelongsToReligion;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\HasManyClassroom;
use App\Traits\Models\BelongsToUser;
use App\Traits\Models\HasManyExamQuestion;
use App\Traits\Models\HasManyStudentQuestionAnswer;
use App\Traits\Models\HasManyStudentViolation;
use App\Traits\Models\HasManyTask;
use App\Traits\Models\HasManyTopic;
use App\Traits\Models\MorphManyAttendance;
use App\Traits\Models\MorphManyRfid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory,
        HasUuids,
        BelongsToUser,
        BelongsToReligion,
        HasManyClassroom,
        HasManyTeacherSubject,
        HasManyExtracurricular,
        MorphManyAttendance,
        MorphManyRfid,
        HasManyStudentViolation,
        HasManyTask,
        HasManyTopic,
        HasManyExamQuestion,
        HasManyStudentQuestionAnswer;

    protected $guarded = ['id'];
    protected $casts = [
        'gender' => GenderEnum::class,
    ];
}
