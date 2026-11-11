<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\ClassroomStudentInterface;
use App\Enums\GraduationStatusEnum;
use App\Models\ClassroomStudent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ClassroomStudentRepository extends BaseRepository implements ClassroomStudentInterface
{
    public function __construct(ClassroomStudent $classroomStudent)
    {
        $this->model = $classroomStudent;
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
        return $this->model->query()->where('student_id', $id)->first();
    }

    public function update(mixed $id, array $data): mixed
    {
        return $this->model->query()->findOrFail($id)->update($data);
    }

    public function delete(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id)->delete();
    }

    public function paginate(): mixed
    {
        return $this->model->query()->latest()->paginate(10);
    }

    public function where(mixed $data, Request $request): mixed
    {
        return $this->model->query()
            ->where('classroom_id', $data)
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('student.user', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                });
            })
            ->paginate(10);
    }

    public function whereStudent(mixed $id): mixed
    {
        return $this->model->query()->where('student_id', $id)->first();
    }


    public function whereClassroom(mixed $id, Request $request): mixed
    {
        return $this->model->query()->where('classroom_id', $id)
            ->when($request->has('search'), function ($query) use ($request) {
                $query->whereHas('student.user', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                });
            })
            ->when($request->has('name'), function ($query) use ($request) {
                $query->whereHas('student.user', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->name . '%');
                });
            })
            ->get();
    }

    public function activeStudents(): mixed
    {
        return $this->model->query()
            ->with('student.modelHasRfid')
            ->whereHas('classroom.schoolYear', function ($q) {
                $q->where('active', 1);
            })
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function getAlumnus(Request $request): mixed
    {
        return $this->model->query()
            ->when($request->name, function ($query) use ($request) {
                $query->whereHas('student.user', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->name . '%');
                });
            })
            ->when($request->gender, function ($query) use ($request) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('gender', $request->gender);
                });
            })
            ->whereRelation('classroom.levelClass', 'name', 'Alumni')
            ->when($request->class, function ($query) use ($request) {
                $query->whereHas('classroom', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->class . '%');
                });
            })
            ->latest()
            ->get();
    }

    public function getByClassId(mixed $id): mixed
    {
        return $this->model->query()
            ->where('classroom_id', $id)
            ->with('student.user')
            ->get();
    }

    public function getByClassIdWithSearch(string $id, Request $request): Collection
    {
        return $this->model->query()
            ->where('classroom_id', $id)
            ->when($request->search, function ($q) use ($request) {
                $q->whereRelation('student.user', 'name', 'like', '%' . $request->search. '%');
            })
            ->with('student.user')
            ->get();
    }

    public function check(mixed $classroomId, mixed $studentId): mixed
    {
        return $this->model->query()->where('classroom_id', $classroomId)->where('student_id', $studentId)->first();
    }

    /**
     * get by student with filter by school_year_id
     *
     * @param string $student_id
     * @param Request $request
     * @return ?ClassroomStudent
     */
    public function getByStudent(string $student_id, Request $request): ?ClassroomStudent
    {
        return $this->model->query()
            ->where('student_id', $student_id)
            ->when($request->school_year_id, function($query) use ($request) {
                $query->whereRelation('classroom', 'school_year_id', $request->school_year_id);
            })
            ->unless($request->school_year_id, function($query) {
                $query->whereRelation('classroom.schoolYear', 'active', 1);
            })
            ->first();
    }

    /**
     * get data where in and search
     *
     * @param array $classroom_ids
     * @param Request $request
     * @return Collection
     */
    public function whereInAndSearch(array $classroom_ids, Request $request): Collection
    {
        return $this->model->query()
            ->with(['student.user'])
            ->whereIn('classroom_id', $classroom_ids)
            ->when($request->search, function($query, $search) {
                $query->whereRelation('student.user', 'name', 'like', "%{$search}%");
            })
            ->get();
    }

    /**
     * get classroom student by where in classroom and with graduation relations with paginate
     * 
     * @param array $classroomIds
     * @param Request $request
     * @param int $paginate
     * @return array
     */
    public function getStudentGraduates(array $classroomIds, Request $request, int $paginate = 50): array
    {
        // Query untuk pagination
        $query = $this->model->query()
            ->with([
                'student.graduationDetails',
                'student.user',
                'classroom.levelClass',
            ])
            ->whereIn('classroom_id', $classroomIds)
            ->when($request->search, function ($query, $search) {
                $query->whereRelation(
                    'student.user',
                    'name',
                    'LIKE',
                    '%' . $search . '%'
                );
            });

        // Counts
        $counts = (clone $query)
            ->join('students', 'students.id', '=', 'classroom_students.student_id')
            ->leftJoin(
                'graduation_details',
                'graduation_details.student_id',
                '=',
                'students.id'
            )
            ->selectRaw("
                COUNT(
                    CASE
                        WHEN graduation_details.status = ?
                        THEN 1
                    END
                ) as passed_count,
                COUNT(
                    CASE
                        WHEN graduation_details.status = ?
                        THEN 1
                    END
                ) as not_passed_count
            ", [
                GraduationStatusEnum::PASSED->value,
                GraduationStatusEnum::NOT_PASSED->value,
            ])
            ->first();

        // Paginated students
        $students = $query->paginate($paginate);

        // ========================================
        // AMBIL SEMUA DATA (TANPA PAGINATION)
        // Untuk original_graduation_data
        // ========================================
        $allStudentsQuery = $this->model->query()
            ->with('student.graduationDetails')
            ->whereIn('classroom_id', $classroomIds)
            ->get();

        $originalGraduationData = $allStudentsQuery
            ->mapWithKeys(function ($classroomStudent) {
                $status = $classroomStudent
                    ->student
                    ->graduationDetails
                    ->first()
                    ?->status;
                
                return [
                    $classroomStudent->student_id => $status
                ];
            })
            ->toArray();

        return [
            'students' => $students,
            'passed_count' => $counts->passed_count ?? 0,
            'not_passed_count' => $counts->not_passed_count ?? 0,
            'original_graduation_data' => $originalGraduationData,
        ];
    }

    /**
     * get student graduates count by classroom
     * 
     * @param array $classroomIds
     * @return int
     */
    public function getStudentGraduatesCount(array $classroomIds): int
    {
        return $this->model->query()
            ->whereIn('classroom_id', $classroomIds)
            ->count();
    }
}
