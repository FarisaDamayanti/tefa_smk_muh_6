<?php

namespace App\Services\School;

use App\Contracts\Repositories\StudentExamRepository;
use App\Contracts\Repositories\StudentQuestionAnswerRepository;
use App\Enums\UserExamStatus;
use App\Http\Requests\School\ResetManyRequest;
use App\Models\StudentExam;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class StudentExamService
{
    public function __construct
    (
        private StudentExamRepository $studentExam,
        private StudentQuestionAnswerRepository $studentQuestionAnswer,
    ){}

    /**
     * logic reset student exam
     *
     * @param StudentExam $studentExam
     * @return void
     */
    public function reset(StudentExam $studentExam): void
    {
        $this->studentQuestionAnswer->deleteByStudentExam($studentExam->id);
        $this->studentExam->delete($studentExam->id);

        $cacheName = "exam_{$studentExam->exam_id}_student_{$studentExam->student_id}";
        $path = "exams/{$cacheName}.json";

        Cache::forget($cacheName);
        Storage::disk('local')->delete($path);
    }

    /**
     * logic reset many
     *
     * @param ResetManyRequest $request
     * @return void
     */
    public function resetMany(ResetManyRequest $request): void
    {
        $validated = $request->validated();
        $ids = $validated['student_exam_ids'];

        $this->studentQuestionAnswer->deleteWhereInByStudentExamId($ids);
        $this->studentExam->deleteWhereInById($ids);

        $studentExams = $this->studentExam->whereInById($ids);

        foreach ($studentExams as $studentExam) {
            $cacheName = "exam_{$studentExam->exam_id}_student_{$studentExam->student_id}";
            $path = "exams/{$cacheName}.json";

            Cache::forget($cacheName);
            Storage::disk('local')->delete($path);
        }
    }
}
