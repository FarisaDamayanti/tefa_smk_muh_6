<?php

namespace App\Models;

use App\Traits\Models\BelongsToTaskQuestionOption;
use App\Traits\Models\BelongsToTaskSubmissionQuestion;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskSubmissionQuestionOption extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToTaskQuestionOption,
        BelongsToTaskSubmissionQuestion;

    protected $guarded = ['id'];
    protected $fillable = [
        'task_submission_question_id',
        'task_question_option_id'
    ];
}
