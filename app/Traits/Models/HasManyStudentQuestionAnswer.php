<?php

namespace App\Traits\Models;

use App\Models\StudentQuestionAnswer;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyStudentQuestionAnswer {
    /**
     * Get the user that owns the BelongsToStudentQuestionAnswers
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentQuestionAnswers(): HasMany
    {
        return $this->hasMany(StudentQuestionAnswer::class);
    }
}
