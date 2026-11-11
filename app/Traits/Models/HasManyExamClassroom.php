<?php

namespace App\Traits\Models;

use App\Models\ExamClassroom;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyExamClassroom {
    /**
     * Get the user that owns the HasManyExamClassrooms
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function examClassrooms(): HasMany
    {
        return $this->hasMany(ExamClassroom::class);
    }
}
