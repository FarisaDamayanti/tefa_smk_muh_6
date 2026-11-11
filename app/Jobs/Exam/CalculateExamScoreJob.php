<?php

namespace App\Jobs\Exam;

use App\Contracts\Repositories\ExamQuestionRepository;
use App\Contracts\Repositories\StudentAnswerMatchingRepository;
use App\Contracts\Repositories\StudentAnswerOptionRepository;
use App\Contracts\Repositories\StudentQuestionAnswerRepository;
use App\Enums\ExamQuestionTypeEnum;
use App\Enums\UserExamStatus;
use App\Http\Requests\Student\StoreExamRequest;
use App\Models\Exam;
use App\Models\StudentExam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CalculateExamScoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $studentExam;
    protected $exam;
    protected $answers;
    
    /**
     * Create a new job instance.
     */
    public function __construct(StudentExam $studentExam, Exam $exam, array $answers)
    {
        $this->studentExam = $studentExam;
        $this->exam = $exam;
        $this->answers = $answers;
    }

    /**
     * Execute the job.
     */
    public function handle(
        ExamQuestionRepository $examQuestionRepository,
        StudentQuestionAnswerRepository $studentQuestionAnswerRepository,
        StudentAnswerOptionRepository $studentAnswerOptionRepository,
        StudentAnswerMatchingRepository $studentAnswerMatchingRepository
    ): void
    {
        $cacheName = "exam_{$this->exam->id}_student_{$this->studentExam->student_id}";
        $path = "exams/{$cacheName}.json";

        $examData = Cache::get($cacheName);

        if (!$examData) {
            if (!Storage::disk('local')->exists($path)) {
                Log::error("Exam data tidak ditemukan di cache maupun file: {$path}");
                return;
            }
            $examData = json_decode(Storage::disk('local')->get($path), true);
        }

        if (!$examData || empty($examData['questions'])) {
            Log::error('Exam data kosong atau tidak valid.');
            return;
        }

        $questionIds = collect($examData['questions'])->pluck('id')->toArray();
        $studentAnswers = collect($this->answers ?? [])->keyBy('id');
        $examQuestions = $examQuestionRepository->getAnswerwhereIn($questionIds);

        $correctChoices = 0;
        $studentQuestionAnswer = [];
        $studentAnswerOptions = [];
        $studentAnswerMatchings = [];
        $hasEssay = false;

        $totalQuestionChoice = 0;

        $totalScoreCorrectAll = 0;
        $studentScore = 0;

        $now = now();

        foreach ($questionIds as $questionId) {
            $studentAnswer = $studentAnswers[$questionId] ?? null;

            $selectedAnswer = $studentAnswer['answers'] ?? null;
            $question = $examQuestions[$questionId];

            $isCorrect = null;
            $answerText = null;

            $studentQuestionAnswerId = (string) Str::uuid();

            if ($question->type == ExamQuestionTypeEnum::SINGLE_CHOICE->value || $question->type == ExamQuestionTypeEnum::MULTIPLE_CHOICE->value) {
                $correctAnswer = $question->sortExamQuestionOptions->toArray();

                $studentOptions = collect($selectedAnswer)
                    ->pluck('option_id')
                    ->sort()
                    ->values();

                $correctOptions = collect($correctAnswer)
                    ->pluck('id')
                    ->sort()
                    ->values();

                $isCorrect = $studentOptions == $correctOptions;

                if ($isCorrect) {
                    $correctChoices++;
                }

                foreach ($studentOptions as $studentOption) {
                    $studentAnswerOptions[] = [
                        'id' => (string) Str::uuid(),
                        'student_question_answer_id' => $studentQuestionAnswerId,
                        'exam_question_option_id' => $studentOption,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                $totalQuestionChoice++;
            } else if ($question->type == ExamQuestionTypeEnum::MATCHING->value) {
                $correctMatchings = $question->rightExamQuestionMatchingsRandomized
                    ->map(fn($i) => $i->rightItemAnswer)
                    ->filter()
                    ->map(fn($i) => $i->left_item_id . '-' . $i->right_item_id)
                    ->sort()
                    ->values();

                $studentMatchings = collect($selectedAnswer)
                    ->map(fn($i) => $i['left_item_id'].'-'.$i['right_item_id'])
                    ->sort()
                    ->values();

                $totalScoreCorrectAll += $question->score;
                $isCorrect = $studentMatchings == $correctMatchings;

                if ($isCorrect) {
                    $studentScore += $question->score;
                }

                foreach ($selectedAnswer ?? [] as $studentMatching) {
                    $studentAnswerMatchings[] = [
                        'id' => (string) Str::uuid(),
                        'student_question_answer_id' => $studentQuestionAnswerId,
                        'left_item_id' => $studentMatching['left_item_id'],
                        'right_item_id' => $studentMatching['right_item_id'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            } else {
                $totalScoreCorrectAll += $question->score;
                $answerText = $studentAnswer['answers_text'] ?? null;
                $hasEssay = true;
            }

            $studentQuestionAnswer[] = [
                'id' => $studentQuestionAnswerId,
                'student_exam_id' => $this->studentExam->id,
                'exam_question_id' => $questionId,
                'answer_text' => $answerText,
                'is_correct' => $isCorrect,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $studentQuestionAnswerRepository->bulkInsert($studentQuestionAnswer);

        if (!empty($studentAnswerOptions)) {
            $studentAnswerOptionRepository->bulkInsert($studentAnswerOptions);
        }
        if (!empty($studentAnswerMatchings)) {
            $studentAnswerMatchingRepository->bulkInsert($studentAnswerMatchings);
        }

        if ($totalQuestionChoice > 0) {
            $scorePerChoice = (100 - $totalScoreCorrectAll) / $totalQuestionChoice;
            $studentScore += ($correctChoices * $scorePerChoice);
        } else {
            $studentScore = ($studentScore / $totalScoreCorrectAll) * 100;
        }

        $status = null;
        if (!$hasEssay) {
            $status = $studentScore >= $this->exam->minimum_score
                ? UserExamStatus::PASSED->value
                : UserExamStatus::NOT_PASSED->value;
        } else {
            $status = UserExamStatus::GRADING->value;
        }

        $this->studentExam->update([
            'score' => $studentScore,
            'status' => $status,
            'finished_at' => $now
        ]);

        Cache::forget($cacheName);
        Storage::disk('local')->delete($path);
    }
}
