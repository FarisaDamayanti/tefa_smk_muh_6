<?php

namespace App\Services\School;

use App\Contracts\Repositories\ExamQuestionRepository;
use App\Contracts\Repositories\ExamRepository;
use App\Http\Requests\School\ActivateExamQuestionRequest;
use App\Models\QuestionBank;

class ExamQuestionService
{
    public function __construct(
        private ExamQuestionRepository $examQuestion,
        private ExamRepository $exam
    ) {}

    public function store(): void {}

    public function update(): void {}

    public function activateQuestion(ActivateExamQuestionRequest $request, QuestionBank $questionBank): mixed
    {
        $questionIds =  $request->validated()['questions'];
        $other = $this->examQuestion->getAllByQuestionBank($questionBank->id);

        $this->examQuestion->bulkUpdate($other->pluck('id')->toArray(), ['is_active' => false]);
        $this->examQuestion->bulkUpdate($questionIds, ['is_active' => true]);

        $total = count($questionIds);

        $this->exam->updateWhere(
            [
                'question_bank_id' => $questionBank->id,
                'exam_date' => ['>=', now()],
                'total_questions' => ['>', $total]
            ], 
            ['total_questions' => $total]
        );

        return $questionIds;
    }
}
