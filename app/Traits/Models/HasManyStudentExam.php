<?php

namespace App\Traits\Models;

use App\Models\StudentExam;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyStudentExam {
    /**
     * Get the user that owns the HasManyStudentExams
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentExams(): HasMany
    {
        return $this->hasMany(StudentExam::class);
    }
}
