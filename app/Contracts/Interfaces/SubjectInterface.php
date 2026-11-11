<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\PaginateInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface SubjectInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface, PaginateInterface
{
    public function whereSchool(Request $request): mixed;
    public function whereTeacher(string|int $employee): mixed;

    /**
     * get by teacher and search
     * 
     * @param string|int $employee
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function whereTeacherSearch(string|int $employee, Request $request): LengthAwarePaginator;
    
    public function count() : mixed;
    public function duplicate(mixed $query): mixed;

    /**
     * contract get subject by classroom id with search
     *
     * @param string $classroom_id
     * @param Request $request
     * @return Collection
     */
    public function getByClassroom(string $classroom_id, Request $request): Collection;
    /**
     * Get subjects belongs to a teacher
     *
     * @param string $employee_id
     * @return Collection
     */
    public function getByEmployee(string $employee_id): Collection;

    /**
     * get all data or filter get by id
     * 
     * @param Request $request
     * @return Collection
     */
    public function getAndFilterById(Request $request): Collection;
}
