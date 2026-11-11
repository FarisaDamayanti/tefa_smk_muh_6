<?php

namespace App\Traits\Models;

use App\Models\TaskSubmissionQuestion;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyTaskSubmissionQuestion
{
    /**
     * Get all of taskSubmissionQuestions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskSubmissionQuestions(): HasMany
    {
        return $this->hasMany(TaskSubmissionQuestion::class);
    }
}
