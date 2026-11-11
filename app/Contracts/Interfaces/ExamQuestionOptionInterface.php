<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;

interface ExamQuestionOptionInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface
{
    /**
     * query bulk update
     *
     * @param string $examQuestionId
     * @param array $options
     * @return int
     */
    public function bulkUpdateByExamQuestion(string $examQuestionId, array $options): int;

    /**
     * query delete where in
     *
     * @param array $data
     * @return int|mixed
     */
    public function deleteWhereIn(array $data): int;
}
    