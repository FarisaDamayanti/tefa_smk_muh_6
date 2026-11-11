<?php

namespace App\Traits\Models;

use App\Models\ExamQuestionMatchingAnswer;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasOneExamQuestionMatchingAnswer {

    /**
     * Get all of the ExamQuestionMatchingAnswer for the HasManyLeftItemAnswer
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function leftItemAnswer(): HasOne
    {
        return $this->hasOne(ExamQuestionMatchingAnswer::class, 'left_item_id');
    }

    /**
     * Get all of the ExamQuestionMatchingAnswer for the HasManyRightItemAnswer
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rightItemAnswer(): HasOne
    {
        return $this->hasOne(ExamQuestionMatchingAnswer::class, 'right_item_id');
    }
}
