<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\PaginateInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Contracts\Interfaces\Eloquent\WhereInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ClassroomInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface, PaginateInterface
{
    public function whereInSchoolYears(Request $request): mixed;
    public function whereSchoolYears(Request $request);
    public function classroomAttendance($classroomIds);
    public function where(Request $request, mixed $data): mixed;
    public function countClass(): mixed;
    public function getAlumni(Request $request): mixed;
    public function search(Request $request): mixed;
    public function insert(array $data): mixed;
    public function duplicate(mixed $query) : mixed;
    public function whereLessonSchedule(Request $request): mixed;
    public function whereEmployeeId(mixed $employee_id): mixed;
    public function getByActiveSchoolYear(): mixed;

    /**
     * query get by level class and school year
     *
     * @param string $level_class_id
     * @param string $school_year_id
     * @return Collection
     */
    public function getByLevelClassAndSchoolYear(string $level_class_id, string $school_year_id): Collection;

    /**
     * get all classroom or filter by school year
     * 
     * @param Request $request
     * @return Collection
     */
    public function getByFilterWithRelation(Request $request): Collection;
    
     /**
     * get classroom by class level and school year
     * 
     * @param string $class_level
     * @param string $school_year_id
     * @return Collection
     */
    public function getByClassLevelAndSchoolYear(string $class_level, string $school_year_id): Collection;
}
