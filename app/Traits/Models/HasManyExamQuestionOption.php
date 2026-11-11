<?php

namespace App\Traits\Models;

use App\Models\ExamQuestionOption;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyExamQuestionOption {

    /**
     * Get all of the examQuestionOption for the HasManyExamQuestionOption sort by option_label
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sortExamQuestionOptions(): HasMany
    {
        return $this->hasMany(ExamQuestionOption::class)->orderBy('option_label', 'asc');
    }

    /**
     * Get all of the examQuestionOption for the HasManyExamQuestionOption random order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function randomQuestionOptions(): HasMany
    {
        return $this->hasMany(ExamQuestionOption::class)->inRandomOrder();
    }

    /**
     * Get all of the examQuestionOption for the HasManyExamQuestionOption correct is true
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function correctQuestionOptions(): HasMany
    {
        return $this->hasMany(ExamQuestionOption::class)->where('is_correct', 1);
    }
}
