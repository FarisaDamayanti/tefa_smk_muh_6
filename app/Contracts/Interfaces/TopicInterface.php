<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\SearchInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Contracts\Interfaces\Eloquent\WhereInterface;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TopicInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface
{
    /**
     * Get by student and subject id
     *
     * @param Request $request
     * @param string $studentId
     * @param string $subjectId
     * @param string $classroomId
     * @return LengthAwarePaginator
     */
    public function getBySubject(Request $request, string $studentId, string $subjectId, string $classroomId): LengthAwarePaginator;
    /**
     * get data by employee_id, subject_id, classroom_id and filter
     *
     * @param string $employee_id
     * @param string $subject_id
     * @param string $classroom_id
     * @param Request $request
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function getByEmployeeSubjectClassroom(string $employee_id, string $subject_id, string $classroom_id, Request $request, int $paginate = 10): LengthAwarePaginator;

    /**
     * get by classroom and subject with relation and sort by topic number
     *
     * @param string $classroomId
     * @param string $subjectId
     * @param Request $request
     * @return Collection
     */
    public function getByClassroomAndSubject(string $classroomId, string $subjectId, Request $request): Collection;

    /**
     * get count by
     *
     * @param string $subject_id
     * @param string $employee_id
     * @param string $classroom_id
     * @param string $semester
     * @return int
     */
    public function getCountWithSpesificParam(array $criteria): int;

    /**
     * reorder topic number (use in delete topic)
     *
     * @param array $criteria
     * @return void
     */
    public function reorderTopicNumber(array $criteria): void;
}
