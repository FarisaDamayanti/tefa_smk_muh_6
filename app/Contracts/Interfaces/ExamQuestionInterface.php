<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ExamQuestionInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface
{
    /**
     * get counting by filter subject_id, level_class_id, school_year_id, and semester
     *
     * @param int $subject_id
     * @param int $level_class_id
     * @param int $school_year_id
     * @param string $semester
     * @return int
     */
    public function getCountWithFilter(int $subject_id, int $level_class_id, int $school_year_id, string $semester): int;
    /**
     * Get count by employee and question bank
     *
     * @param string $questionBankId
     * @param string $employeeId
     * @return integer
     */
    public function getTotalSubmittedQuestions(string $questionBankId, string $employeeId): int;

    /**
     * get question by question bank and random order
     *
     * @param string $question_bank_id
     * @param integer $limit_question
     * @return Collection
     */
    public function getByQuestionBankRandomOrder(string $question_bank_id, int $limit_question): Collection;
    /**
     * Determine are question in given id has been used or not
     *
     * @param array|string $id
     * @return boolean
     */
    public function hasUsed(array|string $id): bool;
    /**
     * Search with unpaginated result
     *
     * @param Request $request
     * @return Collection
     */
    public function searchGet(Request $request): Collection;
    /**
     * Insert
     *
     * @param array[] $data
     * @return mixed
     */
    public function insert(array $data): mixed;
    /**
     * Get all record in given id with relations
     *
     * @param array $ids
     * @return Collection
     */
    public function whereInGetWith(array $ids): Collection;
}
