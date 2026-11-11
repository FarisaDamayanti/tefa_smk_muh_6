<?php

namespace App\Services\Teacher;

use App\Contracts\Repositories\ExamQuestionRepository;
use App\Contracts\Repositories\StudentExamRepository;
use App\Contracts\Repositories\StudentQuestionAnswerRepository;
use App\Enums\ExamQuestionTypeEnum;
use App\Enums\UserExamStatus;
use App\Http\Requests\Teacher\ExamQuestionEssayRequest;
use App\Http\Requests\Teacher\GradingExamQuestionEssayRequest;
use App\Models\QuestionBank;
use App\Models\StudentQuestionAnswer;

class ExamQuestionEssayService
{
    public function __construct(
        private ExamQuestionRepository $examQuestion,
        private StudentQuestionAnswerRepository $studentQuestionAnswer,
        private StudentExamRepository $studentExam,

        private ExamQuestionService $service
    ) {}

    public function store(ExamQuestionEssayRequest $request, QuestionBank $questionBank): void
    {
        $this->service->canAdd($questionBank, 1);

        $data = $request->validated();
        $data['employee_id'] = auth_employee()->id;
        $data['question_bank_id'] = $questionBank->id;
        $data['type'] = ExamQuestionTypeEnum::ESSAY->value;
        $this->examQuestion->store($data);
    }

    public function grading(GradingExamQuestionEssayRequest $request, StudentQuestionAnswer $studentQuestionAnswer): void
    {
        $totalQuestionChoice = $this->studentExam->getSingleAndMultipleChoiceCount($studentQuestionAnswer->student_exam_id);
        $studentExam = $studentQuestionAnswer->studentExam;

        if ($totalQuestionChoice > 0) {
            $studentScore = ($studentExam->score - $studentQuestionAnswer->score) + $request->score;
        } else {
            $scoreExamQuestion = $this->studentQuestionAnswer->getTotalScoreExamQuestion($studentExam->id, $studentQuestionAnswer->id);
            $studentScore = ($scoreExamQuestion->total_score_current ?? 0) + $request->score;

            $studentScore = ($studentScore / $scoreExamQuestion->total_score_max) * 100;
        }

        $this->studentQuestionAnswer->update($studentQuestionAnswer->id, [
            'employee_id' => auth_employee()->id,
            'score' => $request->score,
            'is_correct' => $request->score > 0 ? true : false,
        ]);

        $essayNotYetRatedCount = $this->studentQuestionAnswer->getQuestionEssayNotYetRatedCount($studentExam->id);

        $status = $studentExam->status;

        if ($essayNotYetRatedCount == 0) {
            $status = $studentScore >= $studentExam->exam->minimum_score
                ? UserExamStatus::PASSED->value
                : UserExamStatus::NOT_PASSED->value;
        }

        $this->studentExam->update($studentExam->id, [
            'score' => $studentScore,
            'status' => $status,
        ]);
    }
}
