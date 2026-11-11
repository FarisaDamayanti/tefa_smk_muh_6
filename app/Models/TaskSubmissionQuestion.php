<?php

namespace App\Models;

use App\Traits\Models\BelongsToTaskQuestion;
use App\Traits\Models\BelongsToTaskSubmission;
use App\Traits\Models\HasManyTaskSubmissionQuestionOption;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskSubmissionQuestion extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToTaskSubmission,
        BelongsToTaskQuestion,
        HasManyTaskSubmissionQuestionOption;

    protected $guarded = ['id'];
    protected $fillable = [
        'task_submission_id',
        'task_question_id',
        'is_correct'
    ];
}
