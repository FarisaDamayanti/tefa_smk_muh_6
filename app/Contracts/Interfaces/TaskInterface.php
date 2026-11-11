<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface
{
    /**
     * Get by student with their task submissions
     *
     * @param string $student
     * @return Collection
     */
    public function getWithRelation(string $student): Collection;

    /**
     * get latest task filtered by classroom id with limit
     *
     * @param string|null $classroom_id
     * @param int $limit
     * @return Collection
     */
    public function getLatestTask(?string $classroom_id, int $limit): Collection;

    /**
     * query for get task by student and filtering
     *
     * @param string $student_id
     * @param Request $request
     * @param integer $paginate
     * @return LengthAwarePaginator
     */
    public function getAllTaskStudent(string $student_id, Request $request, int $paginate = 10): LengthAwarePaginator;
    /**
     * Get student tasks with incoming deadline of 3 days
     *
     * @param string $student
     * @return Collection
     */
    public function getIncomingDeadlineTasksByStudent(string $student): Collection;
}
