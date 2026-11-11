<?php

namespace App\Traits\Models;

use App\Models\StudentAnswerMatching;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyStudentAnswerMatching {

    /**
     * Get all of the StudentAnswerMatching for the HasManyLeftItem
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leftItem(): HasMany
    {
        return $this->hasMany(StudentAnswerMatching::class, 'left_item_id');
    }

    /**
     * Get all of the StudentAnswerMatching for the HasManyLeftItem
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rightItem(): HasMany
    {
        return $this->hasMany(StudentAnswerMatching::class, 'right_item_id');
    }

    /**
     * Get all of the StudentAnswerMatching for the studentAnswerMatchings
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentAnswerMatchings(): HasMany
    {
        return $this->hasMany(StudentAnswerMatching::class);
    }
}
