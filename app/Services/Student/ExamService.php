<?php

namespace App\Services\Student;

use App\Contracts\Repositories\ExamQuestionRepository;
use App\Contracts\Repositories\StudentExamRepository;
use App\Enums\ExamQuestionTypeEnum;
use App\Enums\UserExamStatus;
use App\Models\Exam;
use App\Models\StudentExam;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ExamService
{
    public function __construct
    (
        private StudentExamRepository $studentExam,
        private ExamQuestionRepository $examQuestion,
    ){}

    /**
     * logic shuffle question, create cache and create file to keep exam data
     *
     * @param Exam $exam
     * @param StudentExam $studentExam
     * @return void
     */
    public function shuffleQuestion(Exam $exam, StudentExam $studentExam): void
    {
        $questions = $this->examQuestion->getByQuestionBankRandomOrder($exam->question_bank_id, $exam->total_questions);

        // Format soal untuk disimpan
        $examData = [
            'exam_id' => $exam->id,
            'student_id' => $studentExam->student_id,
            'student_exam_id' => $studentExam->id,
            'started_at' => $studentExam->started_at->toDateTimeString(),
            'duration' => $exam->duration,
            'end_time' => $exam->getExamEndTime(),
            'questions' => $questions->values()->mapWithKeys(function ($question, $index) {
                $options = [];

                if ($question->type == ExamQuestionTypeEnum::SINGLE_CHOICE->value || $question->type == ExamQuestionTypeEnum::MULTIPLE_CHOICE->value) {
                    $options = $question->randomQuestionOptions
                        ->values()
                        ->mapWithKeys(function ($item, $index) {
                            $label = 'option_' . chr(97 + $index);
                            return [$label => [
                                'id' => $item->id,
                                'text' => $item->option_text
                            ]];
                        })
                        ->toArray();
                } else if ($question->type == ExamQuestionTypeEnum::MATCHING->value) {
                    $options = [
                        'left' => $question->leftExamQuestionMatchingsRandomized
                            ->map->only(['id', 'item_text'])
                            ->values()
                            ->toArray(),

                        'right' => $question->rightExamQuestionMatchingsRandomized
                            ->map->only(['id', 'item_text'])
                            ->values()
                            ->toArray(),
                    ];
                }

                return [
                    $index + 1 => [
                        'id' => $question->id,
                        'question' => $question->question,
                        'type' => $question->type,
                        'options' => $options,
                    ]
                ];
            })->toArray(),
        ];

        // Simpan ke storage
        $filename = "exam_{$exam->id}_student_{$studentExam->student_id}.json";
        Storage::disk('local')->put("exams/{$filename}", json_encode($examData));
        
        // Simpan juga ke cache untuk akses cepat
        Cache::put("exam_{$exam->id}_student_{$studentExam->student_id}", $examData, now()->addHours(6));
    }

    /**
     * logic to create student exam 
     *
     * @param Exam $exam
     * @return array
     */
    public function start(Exam $exam): array
    {
        $student = auth_student();

        $studentExam = $this->studentExam->firstOrCreate(
            [
                'exam_id' => $exam->id,
                'student_id' => $student->id
            ],
            [
                'status' => UserExamStatus::ON_PROGRESS->value,
                'started_at' => now(),
            ]
        );

        // Hanya jalankan shuffleQuestion jika studentExam baru dibuat.
        // Jika siswa memanggil start() lagi (misal refresh), jangan overwrite
        // cache/file yang sudah ada agar tidak merusak job yang sedang berjalan.
        if ($studentExam->wasRecentlyCreated) {
            $this->shuffleQuestion($exam, $studentExam);
        }

        $data = [
            'started' => true,
            'exam_end_time' => $exam->getExamEndTime()->format('Y-m-d H:i'),
            'duration' => $exam->duration,
            'student_exam_id' => $studentExam->id,
            'started_at' => $studentExam->started_at,
        ];

        return $data;
    }

    /**
     * mapping data for index exam
     *
     * @param LengthAwarePaginator $data
     * @return LengthAwarePaginator
     */
    public function mappingIndexExam(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $data->map(function($exam) {
            $studentExam = $exam->studentExams->first();
            
            $now = now();

            $isUpcoming = $exam->exam_date > $now;
            $isOngoing  = $now->between($exam->exam_date, $exam->getExamEndTime());
            $isOnProgress = $studentExam?->status === UserExamStatus::ON_PROGRESS->value;
            $isFinished = $studentExam && $studentExam->status !== UserExamStatus::ON_PROGRESS->value;

            $status = match (true) {
                $isUpcoming => 'Akan datang',
                $isOngoing && $isOnProgress => 'Belum selesai',
                $isOngoing && !$studentExam => 'Belum mengerjakan',
                $isFinished => 'Selesai',
                default => 'Tidak mengerjakan',
            };

            $exam->status = $status;

            return $exam;
        });

        return $data;
    }
}
