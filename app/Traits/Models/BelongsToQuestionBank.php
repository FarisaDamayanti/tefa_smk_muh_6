<?php

namespace App\Traits\Models;

use App\Models\QuestionBank;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToQuestionBank {
    /**
     * Get the user that owns the BelongsToQuestionBank
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function questionBank(): BelongsTo
    {
        return $this->belongsTo(QuestionBank::class);
    }
}
