<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;

interface ExamQuestionMatchingAnswerInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface
{
    public function upsert($values, $uniqueBy, $update): int;
}
