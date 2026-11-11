<?php

namespace App\Traits\Models;

use App\Models\TaskQuestionOption;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTaskQuestionOption
{
    /**
     * Get the user that owns the taskQuestionOption
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taskQuestionOption(): BelongsTo
    {
        return $this->belongsTo(TaskQuestionOption::class);
    }
}
