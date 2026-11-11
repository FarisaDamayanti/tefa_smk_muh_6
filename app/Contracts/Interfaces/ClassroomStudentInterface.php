<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\PaginateInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Contracts\Interfaces\Eloquent\WhereInterface;
use App\Models\ClassroomStudent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface ClassroomStudentInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface, PaginateInterface
{
    public function whereStudent(mixed $id): mixed;
    public function where(mixed $data, Request $request): mixed;
    public function activeStudents() : mixed;
    public function getAlumnus(Request $request): mixed;
    public function getByClassId(mixed $id): mixed;
    public function whereClassroom(mixed $id, Request $request): mixed;
    public function check(mixed $classroomId, mixed $studentId): mixed;
    public function getByClassIdWithSearch(string $id, Request $request): Collection;

    /**
     * get by student with filter by school_year_id
     *
     * @param string $student_id
     * @param Request $request
     * @return ?ClassroomStudent
     */
    public function getByStudent(string $student_id, Request $request): ?ClassroomStudent;

    /**
     * get data where in and search
     *
     * @param array $classroom_ids
     * @param Request $request
     * @return Collection
     */
    public function whereInAndSearch(array $classroom_ids, Request $request): Collection;

    /**
     * get classroom student by where in classroom and with graduation relations with paginate
     * 
     * @param array $classroomIds
     * @param Request $request
     * @param int $paginate
     * @return array
     */
    public function getStudentGraduates(array $classroomIds, Request $request, int $paginate = 50): array;
}
