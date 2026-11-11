<?php

namespace App\Models;

use App\Traits\Models\BelongsToExamQuestion;
use App\Traits\Models\HasManyStudentAnswerOption;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamQuestionOption extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToExamQuestion,
        HasManyStudentAnswerOption;

    protected $guarded = ['id'];

    protected $fillable = [
        'exam_question_id',
        'option_label',
        'option_text',
        'is_correct',
    ];
}
