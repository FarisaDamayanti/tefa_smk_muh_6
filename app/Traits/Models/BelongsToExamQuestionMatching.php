<?php

namespace App\Traits\Models;

use App\Models\ExamQuestionMatching;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToExamQuestionMatching {

    /**
     * Get the user that owns the BelongsToLeftExamQuestionMatching
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leftExamQuestionMatching(): BelongsTo
    {
        return $this->belongsTo(ExamQuestionMatching::class, 'left_item_id');
    }

    /**
     * Get the user that owns the BelongsToLeftExamQuestionMatching
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rightExamQuestionMatching(): BelongsTo
    {
        return $this->belongsTo(ExamQuestionMatching::class, 'right_item_id');
    }
}
