<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\SubjectInterface;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class SubjectRepository extends BaseRepository implements SubjectInterface
{
    public function __construct(Subject $subject)
    {
        $this->model = $subject;
    }

    public function get(): mixed
    {
        return $this->model->query()->get();
    }

    public function store(array $data): mixed
    {
        return $this->model->query()->create($data);
    }

    public function show(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id);
    }

    public function update(mixed $id, array $data): mixed
    {
        return $this->model->query()->findOrFail($id)->update($data);
    }

    public function delete(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id)->delete();
    }

    public function paginate() : mixed
    {
        return $this->model->query()->latest()->paginate(10);
    }

    public function count(): mixed
    {
        return $this->model->query()->count();
    }

    public function whereSchool(Request $request): mixed
    {
        return $this->model->query()
        ->when($request->name, function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' .  $request->name . '%');
        })
        ->latest()->paginate(9);
    }

    public function whereTeacher(string|int $employee): mixed
    {
        return $this->model->query()
            ->whereRelation('teacherSubjects', 'employee_id', $employee)
            ->get();
    }

    /**
     * get by teacher and search
     * 
     * @param string|int $employee
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function whereTeacherSearch(string|int $employee, Request $request): LengthAwarePaginator
    {
        return $this->model->query()
            ->whereHas('teacherSubjects', function ($q) use ($employee) {
                $q->where('employee_id', $employee)
                    ->whereHas('lessonScheadules');
            })
            ->with([
                'teacherSubjects' => function ($query) use ($request, $employee) {
                    $query->where('employee_id', $employee)
                        ->with([
                            'lessonScheadules' => function ($query) use ($request) {
                                $query->with([
                                    'schoolYear',
                                    'classroom.classroomStudents.student'
                                ])
                                    ->when($request->school_year_id, function ($q) use ($request) {
                                        $q->whereRelation('schoolYear', 'id', $request->school_year_id);
                                    },function ($q) {
                                        $q->whereRelation('schoolYear', 'active', 1);
                                    });
                            }
                        ]);
                }
            ])
            ->when($request->school_year_id, function($query) use ($request) {
                $query->whereRelation('teacherSubjects.lessonScheadules', 'school_year_id', $request->school_year_id);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            })
            ->when($request->classroom_id, function ($q) use ($request) {
                $q->whereRelation('teacherSubjects.lessonScheadules', 'classroom_id', $request->classroom_id);
            })
            ->when($request->subject_id, function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            })
            ->paginate($request->query('per_page', 10));
    }

    public function duplicate(mixed $query): mixed
    {
        return $this->model->query()->where('name', 'LIKE', '%' . $query . '%')->first();
    }

    /**
     * get subject by classroom id with search
     *
     * @param string $classroom_id
     * @param Request $request
     * @return Collection
     */
    public function getByClassroom(string $classroom_id, Request $request): Collection
    {
        $subjects = $this->model->query()
            ->with(['teacherSubjects.lessonScheadules.classroom.schoolYear'])
            ->whereRelation('teacherSubjects.lessonScheadules', 'classroom_id', $classroom_id)
            ->when($request->search, function($query) use ($request) {
                $query->where('name', 'like', "%$request->search%");
            })
            ->get();

            return $subjects;
    }

    public function getByEmployee(string $employee_id): Collection
    {
        return $this->model->query()
            ->whereRelation('teacherSubjects.employee', 'id', $employee_id)
            ->get();
    }

    public function getByStudent(string $studentId): Collection
    {
        return $this->model->query()
            ->whereRelation('teacherSubjects.employee.classrooms.classroomStudents', 'student_id', $studentId)
            ->get();
    }

    /**
     * get all data or filter get by id
     * 
     * @param Request $request
     * @return Collection
     */
    public function getAndFilterById(Request $request): Collection
    {
        return $this->model->query()
            ->when($request->subject_id, function($query) use ($request) {
                $query->where('id', $request->subject_id);
            })
            ->get();
    }
}
