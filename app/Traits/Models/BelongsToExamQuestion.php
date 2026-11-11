<?php

namespace App\Traits\Models;

use App\Models\ExamQuestion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToExamQuestion {
    /**
     * Get the user that owns the BelongsToExamQuestion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function examQuestion(): BelongsTo
    {
        return $this->belongsTo(ExamQuestion::class);
    }
}
