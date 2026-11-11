<?php

namespace App\Services\Teacher;

use App\Enums\UserExamStatus;
use App\Http\Resources\Teacher\Exam\ExamRecapShowResource;
use App\Models\Exam;
use App\Traits\PaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ExamService
{
    use PaginationTrait;

    /**
     * logic show exam detail
     *
     * @param Exam $exam
     * @param Collection $classroomStudents
     * @return Collection
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
     * mapping data for function show and response API
     * 
     * @param Exam $exam
     * @param Collection $classroomStudents
     * @return array
     */
    public function mappingShow(Exam $exam, Collection $classroomStudents): array
    {
        $exam = $this->show($exam, $classroomStudents);

        $data['exam'] = ExamRecapShowResource::make($exam);
        $data['exam_statistics'] = $exam->examStatistics;
        $data['classrooms'] = $exam->classrooms;

        return $data;
    }

    /**
     * mapping data for function examDetail and response API
     * 
     * @param Exam $exam
     * @param array $rawData
     * @return array
     */
    public function mappingExamDetail(Exam $exam, array $rawData): array
    {
        $classroom = $exam->examClassrooms->first()->classroom;

        $data['exam'] = ExamRecapShowResource::make($rawData['exam']);
        $data['student_exam_statistics'] = $rawData['exam']->studentExamStatistics;
        $data['classroom_name'] = $classroom->levelClass->name . ' ' . $classroom->name;
        $data['student_exams'] = $rawData['studentExams']->items();
        $data['paginate'] = $this->customPaginate($rawData['studentExams']->currentPage(), $rawData['studentExams']->lastPage(), $rawData['studentExams']->perPage());

        return $data;
    }
}
