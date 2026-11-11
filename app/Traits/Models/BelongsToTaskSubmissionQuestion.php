<?php

namespace App\Traits\Models;

use App\Models\TaskSubmissionQuestion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTaskSubmissionQuestion
{
    /**
     * Get the user that owns the BelongsToTaskSubmission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taskSubmissionQuestions(): BelongsTo
    {
        return $this->belongsTo(TaskSubmissionQuestion::class);
    }
}
