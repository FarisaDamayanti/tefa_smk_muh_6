<?php

namespace App\Models;

use App\Traits\Models\BelongsToEmployee;
use App\Traits\Models\BelongsToExam;
use App\Traits\Models\BelongsToLevelClass;
use App\Traits\Models\BelongsToQuestionBank;
use App\Traits\Models\BelongsToSchoolYear;
use App\Traits\Models\BelongsToSubject;
use App\Traits\Models\HasManyExamQuestionMatching;
use App\Traits\Models\HasManyExamQuestionOption;
use App\Traits\Models\HasManyStudentQuestionAnswer;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamQuestion extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToQuestionBank,
        BelongsToEmployee,
        HasManyStudentQuestionAnswer,
        HasManyExamQuestionOption,
        HasManyExamQuestionMatching;

    protected $guarded = ['id'];

    protected $fillable = [
        'question_bank_id',
        'employee_id',
        'type',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'option_e',
        'answer',
        'is_active',
        'score'
    ];
}
