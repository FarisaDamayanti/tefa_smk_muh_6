<?php

namespace App\Services\Student;

use App\Models\Exam;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ExamQuestionService
{
    /**
     * logic get question by exam and number of question
     *
     * @param Exam $exam
     * @param integer $number
     * @return object
     */
    public function show(Exam $exam): object
    {
        $student = auth_student();

        $examData = Cache::get("exam_{$exam->id}_student_{$student->id}");

        if (!$examData) {
            $filename = "exam_{$exam->id}_student_{$student->id}.json";
            $path = "exams/{$filename}";
            
            if (!Storage::disk('local')->exists($path)) {
                $data = (object) [
                    'success' => false,
                    'message' => 'Soal belum siap.',
                ];

                return $data;
            }
            
            $examData = json_decode(Storage::disk('local')->get($path), true);
        }

        $startTime = now()->parse($examData['started_at']);
        $endTimeFromStart = $startTime->copy()->addMinutes($examData['duration']);

        $remainingTime = now()->diffInSeconds($endTimeFromStart, false);

        $examEndTime = $exam->getExamEndTime();
        $remainingExamEndTime = now()->diffInSeconds($examEndTime, false);

        $data = (object) [
            'questions' => $examData['questions'],
            'question_total' => count($examData['questions']),
            'question_ids' => array_column($examData['questions'], 'id'),
            'student_exam_id' => $examData['student_exam_id'],
            'remaining_time' => $remainingTime,
            'remaining_exam_end_time' => $remainingExamEndTime,
            'end_time' => $examEndTime->format('Y-m-d H:i'),
            'duration' => $exam->duration,
            'success' => true,
        ];

        return $data;
    }
}
