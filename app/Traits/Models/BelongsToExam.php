<?php

namespace App\Traits\Models;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToExam {
    /**
     * Get the user that owns the BelongsToExam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}
