<?php

namespace App\Services\School;

use App\Contracts\Repositories\ExamClassroomRepository;
use App\Contracts\Repositories\ExamRepository;
use App\Enums\UserExamStatus;
use App\Http\Requests\School\StoreExamRequest;
use App\Http\Requests\School\UpdateExamRequest;
use App\Models\Exam;
use App\Traits\PaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ExamService
{
    use PaginationTrait;
    
    public function __construct
    (
        private ExamRepository $exam,
        private ExamClassroomRepository $examClassroom,
    ){}

    /**
     * logic exam create
     *
     * @param StoreExamRequest $request
     * @return array
     */
    public function store(StoreExamRequest $request): array
    {
        $data = $request->validated();

        $data['token'] = strtoupper(Str::random(6));

        $exam = $this->exam->store($data);

        foreach ($request->classroom_ids as $classroom_id) {
            $this->examClassroom->store([
                'classroom_id' => $classroom_id,
                'exam_id' => $exam->id,
            ]);
        }

        return $data;
    }

    /**
     * logic exam update
     *
     * @param UpdateExamRequest $request
     * @param Exam $exam
     * @return array
     */
    public function update(UpdateExamRequest $request, Exam $exam): array
    {
        $data = $request->validated();

        $this->exam->update($exam->id, $data);

        $currentClassroomIds = $exam->examClassrooms()
            ->pluck('classroom_id')
            ->toArray();

        if (!$exam->studentExams()->exists()) {
            $newClassroomIds = $request->classroom_ids ?? [];
            $toDelete = array_diff($currentClassroomIds, $newClassroomIds);
            $toInsert = array_diff($newClassroomIds, $currentClassroomIds);
    
            if (!empty($toDelete)) {
                $this->examClassroom->deleteWhereIn($exam->id, $toDelete);
            }
    
            foreach ($toInsert as $classroomId) {
                $this->examClassroom->store([
                    'exam_id' => $exam->id,
                    'classroom_id' => $classroomId,
                ]);
            }
        }

        return $data;
    }

    /**
     * logic exam delete
     *
     * @param Exam $exam
     * @return void
     */
    public function delete(Exam $exam)
    {
        if ($exam->studentExams()->exists()) {
            return false;
        }

        $this->examClassroom->deleteByExam($exam->id);
        $this->exam->delete($exam->id);
        return true;
    }

    /**
     * generate token in exam
     *
     * @param Exam $exam
     * @return void
     */
    public function generateToken(Exam $exam): void
    {
        $data['token'] = strtoupper(Str::random(6));

        $this->exam->update($exam->id, $data);
    }

    /**
     * mapping exam data
     *
     * @param Exam $exam
     * @param Collection $classroomStudents
     * @param Request $request
     * @return array
     */
    public function show(Exam $exam, Collection $classroomStudents): Exam
    {
        $exam = $this->mappingExamClassroom($exam);
        $exam = $this->mappingDetailExamStatistics($exam, $classroomStudents->count());

        return $exam;
    }

    /**
     * logic shoe exam classroom detail
     *
     * @param Exam $exam
     * @param Collection $classroomStudents
     * @param Request $request
     * @return array
     */
    public function examClassroomDetail(Exam $exam, Collection $classroomStudents, Request $request): array
    {
        $exam = $this->mappingStudentExamStatistics($exam, $exam->examClassrooms->first()->classroom->classroom_students_count);
        $classroomStudents = $this->mappingStudentExams($exam, $classroomStudents, $request);

        return [
            'exam' => $exam,
            'studentExams' => $classroomStudents,
        ];
    }

    /**
     * mapping exam classroom for show exam detail
     *
     * @param Exam $exam
     * @return Exam
     */
    private function mappingExamClassroom(Exam $exam): Exam
    {
        $studentExams = $exam->studentExams;

        $submittedGrouped = $studentExams
            ->where('status', '!=', UserExamStatus::ON_PROGRESS->value)
            ->flatMap(fn($studentExam) => $studentExam->student->classroomStudents)
            ->groupBy('classroom_id')
            ->map->count();

        $data = $exam->examClassrooms->map(function ($examClassroom) use ($submittedGrouped) {
            $classroom = $examClassroom->classroom;

            return [
                'id' => $classroom->id,
                'class_name' => $classroom->levelClass?->name . ' ' . $classroom->name,
                'teacher_name' => $classroom->employee?->user?->name,
                'student_total' => $classroom->classroomStudents?->count(),
                'student_submitted' => $submittedGrouped[$classroom->id] ?? 0,
            ];
        });

        $exam->setAttribute('classrooms', $data);

        return $exam;
    }

    /**
     * mapping attribute detail exam statistics
     *
     * @param Exam $exam
     * @param integer $totalStudent
     * @return Exam
     */
    private function mappingDetailExamStatistics(Exam $exam, int $totalStudent): Exam
    {
        $submittedCount = $exam->studentExams->where('status', '!=', UserExamStatus::ON_PROGRESS->value)->count();

        $exam->setAttribute('examStatistics', [
            'student_submitted' => $submittedCount,
            'student_not_submitted'  => $totalStudent - $submittedCount,
            'total_students' => $totalStudent,
        ]);

        return $exam;
    }

    /**
     * mapping student exam
     *
     * @param Exam $exam
     * @param Collection $classroomStudents
     * @param Request $request
     * @return LengthAwarePaginator|Collection
     */
    private function mappingStudentExams(Exam $exam, Collection $classroomStudents, Request $request): LengthAwarePaginator|Collection
    {
        $studentExamMap = $exam->studentExams->keyBy('student_id');

        $now = now();
        $examEndTime = $exam->getExamEndTime();
        $data = $classroomStudents->map(function ($classroomStudent) use ($studentExamMap, $now, $examEndTime) {
            $student = $classroomStudent->student;
            $studentExam = $studentExamMap->get($student->id);

            return [
                'id' => $student->id,
                'name'  => $student->user->name,
                'student_exam_id' => $studentExam?->id,
                'score' => $studentExam?->score ?? '-',
                'status'=> $studentExam
                    ? match ($studentExam->status) {
                        UserExamStatus::ON_PROGRESS->value => 'Sedang Mengerjakan',
                        UserExamStatus::GRADING->value => 'Belum Dinilai',
                        UserExamStatus::PASSED->value => 'Lulus',
                        UserExamStatus::NOT_PASSED->value => 'Tidak Lulus',
                    }
                    : ($now->isBefore($examEndTime) ? 'Belum Mengerjakan' : 'Tidak Mengerjakan'),
            ];
        });

        if ($request->filled('sortBy')) {
            $data = match ($request->sortBy) {
                'highest' => $data->sortByDesc('score'),
                'lowest'  => $data->sortBy('score'),
                default   => $data,
            };
        }

        if ($request->per_page == 'all') {
            return $data->values();
        }

        return $this->paginateCollection(
            $data->values(),
            $request->query('per_page', 10)
        );
    }

    /**
     * mapping attribute student exam statistics
     *
     * @param Exam $exam
     * @param integer $totalStudent
     * @return Exam
     */
    private function mappingStudentExamStatistics(Exam $exam, int $totalStudent): Exam
    {
        $studentExams = $exam->studentExams;

        $submittedCount = $studentExams->count();

        $average = $studentExams
            ->whereNotNull('score')
            ->avg('score');

        $exam->setAttribute('studentExamStatistics', [
            'student_submitted' => $submittedCount,
            'student_not_submitted' => $totalStudent - $submittedCount,
            'lowest_score' => $studentExams->min('score') ?? 0,
            'highest_score' => $studentExams->max('score') ?? 0,
            'average_score' => $average !== null ? round($average, 2) : 0,
        ]);

        return $exam;
    }
}
