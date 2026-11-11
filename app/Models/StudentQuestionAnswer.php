<?php

namespace App\Models;

use App\Traits\Models\BelongsToEmployee;
use App\Traits\Models\BelongsToExamQuestion;
use App\Traits\Models\BelongsToStudentExam;
use App\Traits\Models\HasManyStudentAnswerMatching;
use App\Traits\Models\HasManyStudentAnswerOption;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentQuestionAnswer extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToStudentExam,
        BelongsToExamQuestion,
        BelongsToEmployee,
        HasManyStudentAnswerOption,
        HasManyStudentAnswerMatching;

    protected $guarded = ['id'];

    protected $fillable = [
        'student_exam_id',
        'exam_question_id',
        'employee_id',
        'answer_text',
        'answer',
        'score',
        'is_correct',
    ];
}
