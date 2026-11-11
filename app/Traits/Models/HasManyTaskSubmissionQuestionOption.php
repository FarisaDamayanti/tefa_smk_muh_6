<?php

namespace App\Traits\Models;

use App\Models\TaskSubmissionQuestionOption;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyTaskSubmissionQuestionOption
{
    /**
     * Get all of taskSubmissionQuestionOptions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskSubmissionQuestionOptions(): HasMany
    {
        return $this->hasMany(TaskSubmissionQuestionOption::class);
    }
}
