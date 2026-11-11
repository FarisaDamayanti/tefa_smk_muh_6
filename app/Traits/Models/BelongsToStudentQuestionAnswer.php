<?php

namespace App\Traits\Models;

use App\Models\StudentQuestionAnswer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToStudentQuestionAnswer {
    /**
     * Get the user that owns the BelongsToStudentQuestionAnswer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function studentQuestionAnswer(): BelongsTo
    {
        return $this->belongsTo(StudentQuestionAnswer::class);
    }
}
