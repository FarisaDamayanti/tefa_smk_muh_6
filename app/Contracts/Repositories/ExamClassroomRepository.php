<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\ExamClassroomInterface;
use App\Models\ExamClassroom;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ExamClassroomRepository extends BaseRepository implements ExamClassroomInterface
{
    public function __construct(ExamClassroom $ExamClassroom)
    {
        $this->model = $ExamClassroom;
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

    /**
     * delete by exam_id
     *
     * @param string $exam_id
     * @return integer
     */
    public function deleteByExam(string $exam_id): int
    {
        return $this->model->query()
            ->where('exam_id', $exam_id)
            ->delete();
    }

    /**
     * query delete where in
     *
     * @param string $exam_id
     * @param array $classroom_ids
     * @return boolean
     */
    public function deleteWhereIn(string $exam_id, array $classroom_ids): bool
    {
        return $this->model->query()
            ->where('exam_id', $exam_id)
            ->whereIn('classroom_id', $classroom_ids)
            ->delete();
    }

    /**
     * Search exam result of student with employee filter
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function searchExamResult(Request $request): LengthAwarePaginator
    {
        return $this->model->query()
            ->whereHas('exam')
            ->when($request->employee_id, function ($query, $employee_id) {
                $query->where(function($query) use ($employee_id) {
                    $query->whereRelation('classroom', 'employee_id', $employee_id)
                        ->orWhereRelation('classroom.lessonSchedule.teacherSubject', 'employee_id', $employee_id);
                });
            })
            ->when($request->search, function ($query, $search) {
                $query->whereRelation(
                    'exam',
                    'title',
                    'like',
                    "%$search%"
                );
            })
            ->when($request->school_year_id, function ($query, $school_year_id) {
                $query->whereRelation(
                    'exam',
                    'school_year_id',
                    $school_year_id
                );
            })
            ->when($request->level_class_id, function ($query, $level_class_id) {
                $query->whereRelation(
                    'classroom',
                    'level_class_id',
                    $level_class_id
                );
            })
            ->when($request->subject_id, function ($query, $subject_id) {
                $query->whereRelation(
                    'exam.questionBank',
                    'subject_id',
                    $subject_id
                );
            })
            ->with([
                'exam.schoolYear',
                'exam.questionBank.subject',
                'classroom.levelClass',
            ])
            ->with([
            'exam' => function ($q1) {
                $q1->withCount([
                    'studentExams as passed_students' => function ($q) {
                        $q->whereColumn('student_exams.score', '>=', 'exams.minimum_score')
                            ->whereHas('student.classroomStudents', function ($query) use ($q) {
                                $query->whereIn('classroom_students.classroom_id', function ($subQuery) use ($q) {
                                    $subQuery->select('exam_classrooms.classroom_id')
                                        ->from('exam_classrooms')
                                        ->whereColumn('exam_classrooms.exam_id', 'exams.id');
                                });
                            });
                    },
                    'studentExams as not_passed_students' => function ($q) {
                        $q->whereColumn('student_exams.score', '<', 'exams.minimum_score')
                            ->whereHas('student.classroomStudents', function ($query) use ($q) {
                                $query->whereIn('classroom_students.classroom_id', function ($subQuery) use ($q) {
                                    $subQuery->select('exam_classrooms.classroom_id')
                                        ->from('exam_classrooms')
                                        ->whereColumn('exam_classrooms.exam_id', 'exams.id');
                                });
                            });
                    },
                ]);
            },
            'classroom'
            ])
            ->orderByDesc(
                \App\Models\Exam::select('exam_date')
                    ->whereColumn('exams.id', 'exam_classrooms.exam_id')
                    ->limit(1)
            )
            ->paginate($request->query('per_page', 10));

    }
}
