<?php

namespace App\Traits\Models;

use App\Models\ExamQuestion;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyExamQuestion {

    /**
     * Get all of the students for the HasManyExamQuestion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function examQuestions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class);
    }

}
