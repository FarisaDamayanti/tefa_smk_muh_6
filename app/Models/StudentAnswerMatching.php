<?php

namespace App\Models;

use App\Traits\Models\BelongsToExamQuestionMatching;
use App\Traits\Models\BelongsToStudentQuestionAnswer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswerMatching extends Model
{
    use HasFactory,
        BelongsToStudentQuestionAnswer,
        BelongsToExamQuestionMatching;

    protected $guarded = ['id'];

    protected $fillable = [
        'student_question_answer_id',
        'left_item_id',
        'right_item_id',
    ];
}
