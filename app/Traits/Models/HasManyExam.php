<?php

namespace App\Traits\Models;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyExam {

    /**
     * Get all of the students for the HasManyExam
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

}
