<?php

namespace App\Models;

use App\Traits\Models\BelongsToTaskQuestion;
use App\Traits\Models\HasManyTaskSubmissionQuestionOption;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskQuestionOption extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToTaskQuestion,
        HasManyTaskSubmissionQuestionOption;

    protected $guarded = ['id'];
    protected $fillable = [
        'task_question_id',
        'option_label',
        'option_text',
        'is_correct'
    ];
}
