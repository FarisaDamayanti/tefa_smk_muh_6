<?php

namespace App\Traits\Models;

use App\Models\StudentAnswerOption;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyStudentAnswerOption {

    /**
     * Get all of the students for the HasManystudentAnswerOptions
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentAnswerOptions(): HasMany
    {
        return $this->hasMany(StudentAnswerOption::class);
    }
}
