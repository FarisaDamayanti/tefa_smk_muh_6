<?php

namespace App\Models;

use App\Traits\Models\BelongsToExamQuestionOption;
use App\Traits\Models\BelongsToStudentQuestionAnswer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswerOption extends Model
{
    use HasFactory,
        BelongsToStudentQuestionAnswer,
        BelongsToExamQuestionOption;
    
    protected $guarded = ['id'];

    protected $fillable = [
        'student_question_answer_id',
        'exam_question_option_id',
    ];
}
