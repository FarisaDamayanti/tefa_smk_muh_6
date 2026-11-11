<?php

namespace App\Traits\Models;

use App\Models\ExamQuestionOption;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToExamQuestionOption {

    /**
     * Get the user that owns the BelongsToExamQuestionOption
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function examQuestionOption(): BelongsTo
    {
        return $this->belongsTo(ExamQuestionOption::class);
    }
}
