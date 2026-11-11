<?php

namespace App\Traits\Models;

use App\Models\StudentExam;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToStudentExam {
    /**
     * Get the user that owns the BelongsToStudentExam
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function studentExam(): BelongsTo
    {
        return $this->belongsTo(StudentExam::class);
    }
}
