<?php

namespace App\Models;

use App\Traits\Models\BelongsToTask;
use App\Traits\Models\HasManyTaskQuestionOption;
use App\Traits\Models\HasManyTaskSubmissionQuestion;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskQuestion extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToTask,
        HasManyTaskQuestionOption,
        HasManyTaskSubmissionQuestion;

    protected $guarded = ['id'];
    protected $fillable = [
        'question',
        'type',
        'task_id'
    ];
}
