<?php

namespace App\Traits\Models;

use App\Models\TaskQuestion;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyTaskQuestion
{
    /**
     * Get all of taskQuestions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskQuestions(): HasMany
    {
        return $this->hasMany(TaskQuestion::class);
    }

}
