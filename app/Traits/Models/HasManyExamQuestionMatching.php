<?php

namespace App\Traits\Models;

use App\Enums\GroupExamQuestionMatchingEnum;
use App\Models\ExamQuestionMatching;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyExamQuestionMatching {

    /**
     * Get all of the examQuestionMatching for the HasManyRightExamQuestionMatching
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rightExamQuestionMatchingsRandomized(): HasMany
    {
        return $this->hasMany(ExamQuestionMatching::class)->where('group', GroupExamQuestionMatchingEnum::RIGHT->value)->inRandomOrder();
    }

    /**
     * Get all of the examQuestionMatching for the HasManyLeftExamQuestionMatching
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leftExamQuestionMatchingsRandomized(): HasMany
    {
        return $this->hasMany(ExamQuestionMatching::class)->where('group', GroupExamQuestionMatchingEnum::LEFT->value)->inRandomOrder();
    }

    /**
     * Get all of the examQuestionMatching on left
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leftExamQuestionMatchings(): HasMany
    {
        return $this->hasMany(ExamQuestionMatching::class)->where('group', GroupExamQuestionMatchingEnum::LEFT->value)->oldest();
    }

    /**
     * Get all of the examQuestionMatching on right
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rightExamQuestionMatchings(): HasMany
    {
        return $this->hasMany(ExamQuestionMatching::class)->where('group', GroupExamQuestionMatchingEnum::RIGHT->value)->oldest();
    }
}
