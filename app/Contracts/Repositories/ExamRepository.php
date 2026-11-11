<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\ExamInterface;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ExamRepository extends BaseRepository implements ExamInterface
{
    public function __construct(Exam $Exam)
    {
        $this->model = $Exam;
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

    public function bulkUpdate(array $ids, array $data): mixed
    {
        return $this->model->query()
            ->whereIn('id', $ids)
            ->update($data);
    }

    /**
     * query search exam
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request): mixed
    {
        $query = $this->model->query()
            ->when(auth_student(), function($query, $student) {
                $query->with(['studentExams' => fn($query) => $query->where('student_id', $student->id)])
                    ->whereRelation('examClassrooms', 'classroom_id', $student->currentStudentClassroom?->classroom_id);
            }, function($query) {
                $query->with(['questionBank']);
            })
            ->when(!$request->export, function($query) use ($request) {
                $query->when($request->search, function($query, $search) {
                    $query->where('title', 'like', "%$search%");
                });
            })
            ->when(auth_employee(), function($query, $employee) {
                $query->whereRelation('questionBank.subject.teacherSubjects', 'employee_id', $employee->id);
            })
            ->when($request->subject_id, function($query, $subject_id) {
                $query->whereRelation('questionBank', 'subject_id', $subject_id);
            })
            ->when($request->level_class_id, function($query, $level_class_id) {
                $query->whereRelation('questionBank', 'level_class_id', $level_class_id);
            })
            ->when($request->semester, function($query, $semester) {
                $query->where('semester', $semester);
            })
            ->when($request->school_year_id, function($query, $school_year_id) {
                $query->where('school_year_id', $school_year_id);
            })
            ->orderBy('exam_date', 'desc');

        return $request->export
            ? $query->get()
            : $query->paginate($request->query('per_page', 10));
    }

    /**
     * Update in given condition
     *
     * @param array $attributes
     * @param array $data
     * @return integer
     */
    public function updateWhere(array $attributes, array $data): int
    {
        $query = $this->model->newQuery();

        foreach ($attributes as $key => $value) {

            if (is_array($value) && count($value) === 2) {
                [$operator, $val] = $value;
                $query->where($key, $operator, $val);
                continue;
            }

            $query->where($key, $value);
        }

        return $query->update($data);
    }

    /**
     * Search exam with teacher
     *
     * @param Request $request
     * @return Collection
     */
    public function searchDetail(Request $request): Collection
    {
        return $this->model->query()
            ->when($request->search, function ($query, $search) {
                $query->where(
                    'title',
                    'like',
                    "%$search%"
                );
            })
            ->when($request->employee_id, function ($query, $employee_id) {
                $query->whereRelation(
                    'questionBank.subject.teacherSubjects',
                    'employee_id',
                    $employee_id
                );
            })
            ->when($request->school_year_id, function ($query, $school_year_id) {
                $query->where(
                    'school_year_id',
                    $school_year_id
                );
            })
            ->when($request->level_class_id, function ($query, $level_class_id) {
                $query->whereRelation(
                    'examClassrooms.classroom',
                    'level_class_id',
                    $level_class_id
                );
            })
            ->when($request->subject_id, function ($query, $subject_id) {
                $query->whereRelation(
                    'questionBank',
                    'subject_id',
                    $subject_id
                );
            })
            ->with([
                'questionBank.subject'
            ])
            ->withCount([
                'studentExams as passed_students' => function ($q) use ($request) {
                    $q->whereColumn(
                        'student_exams.score',
                        '>=',
                        'exams.minimum_score'
                    )
                    ->when($request->employee_id, function ($query, $employee_id) {
                        $query->whereRelation(
                            'student.classroomStudents.classroom',
                            'employee_id',
                            $employee_id
                        );
                    });
                },
                'studentExams as not_passed_students' => function ($q) use ($request) {
                    $q->whereColumn(
                        'student_exams.score',
                        '<',
                        'exams.minimum_score'
                    )
                    ->when($request->employee_id, function ($query, $employee_id) {
                        $query->whereRelation(
                            'student.classroomStudents.classroom',
                            'employee_id',
                            $employee_id
                        );
                    });;
                }
            ])
            ->get();
    }


    /**
     * Get student's incoming exam within 4 days before exam
     *
     * @param string $student
     * @return Collection
     */
    public function getInComingExamByStudent(string $student): Collection
    {
        return $this->model->query()
            ->whereRelation('examClassrooms.classroom.classroomStudents', 'student_id', $student)
            ->where('exam_date', '>=', now())
            ->where('exam_date', '<=', now()->addDays(4))
            ->get();
    }
}
