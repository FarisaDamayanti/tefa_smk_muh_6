<?php

namespace App\Models;

use App\Traits\Models\BelongsToExamQuestion;
use App\Traits\Models\HasOneExamQuestionMatchingAnswer;
use App\Traits\Models\HasManyStudentAnswerMatching;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamQuestionMatching extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToExamQuestion,
        HasOneExamQuestionMatchingAnswer, // leftItemAnswer, rightItemAnswer
        HasManyStudentAnswerMatching; // leftItem, rightItem

    protected $guarded = ['id'];

    protected $fillable = [
        'exam_question_id',
        'item_text',
        'group',
    ];
}
