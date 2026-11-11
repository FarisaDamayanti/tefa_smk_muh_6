<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\PaginateInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface LessonScheduleInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface, PaginateInterface
{
    public function whereClassroom(mixed $id, string $query): mixed;
    public function whereClassroomId(mixed $id): mixed;
    public function whereTeacher(mixed $id, mixed $day): mixed;
    public function whereTeacherNotif(mixed $id, mixed $day): mixed;
    public function dahsboardSchool(mixed $query, mixed $day): mixed;
    public function groupByLatest($query): mixed;
    public function whereJournalTeacher(mixed $query, Request $request) : mixed;
    public function export(Request $request): mixed;
    public function getByTeacher(mixed $id): mixed;
    public function whereDay(mixed $query): mixed;
    public function whereDayApi(mixed $query): mixed;
    public function existsOtherSchedule(
        string $day,
        string $start,
        string $end,
        string $teacher_subject_id,
        string $school_year_id
    ): bool;
    public function getSameScheduleCount(int $user_id, int $subject_id, int $school_year_id): int;

    /**
     * get classroom_id by employee_id and filter by school_year_id
     * 
     * @param $employee_id
     * @param Request $request
     * @return Collection
     */
    public function getByEmployee(string $employee_id, Request $request): Collection;
}
