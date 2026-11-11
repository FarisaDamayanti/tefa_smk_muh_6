<?php

namespace App\Traits\Models;

use App\Models\TaskQuestionOption;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyTaskQuestionOption
{
    /**
     * Get all of taskQuestionOptions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskQuestionOptions(): HasMany
    {
        return $this->hasMany(TaskQuestionOption::class);
    }

    public function randomTaskQuestionOptions(): HasMany
    {
        return $this->hasMany(TaskQuestionOption::class)->inRandomOrder();
    }
}
