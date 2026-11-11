<?php

namespace App\Services\Teacher;

use App\Contracts\Repositories\ExamQuestionOptionRepository;
use App\Contracts\Repositories\ExamQuestionRepository;
use App\Enums\AnswerOptionEnum;
use App\Enums\ExamQuestionTypeEnum;
use App\Http\Requests\Teacher\StoreExamQuestionChoiceRequest;
use App\Http\Requests\Teacher\UpdateExamQuestionChoiceRequest;
use App\Models\ExamQuestion;
use App\Models\QuestionBank;
use Illuminate\Support\Str;

class ExamQuestionChoiceService
{
    public function __construct
    (
        private ExamQuestionRepository $examQuestion,
        private ExamQuestionOptionRepository $examQuestionOption,
    ){}

    /**
     * logic store exam question single choice and multiple choice
     *
     * @param StoreExamQuestionChoiceRequest $request
     * @param QuestionBank $questionBank
     * @return void
     */
    public function store(StoreExamQuestionChoiceRequest $request, QuestionBank $questionBank): void
    {
        $options = $request->options;

        $correctCount = collect($options)
            ->where('is_correct', true)
            ->count();

        $examQuestion = [
            'question_bank_id' => $questionBank->id,
            'employee_id' => auth_employee()->id,
            'type' => $correctCount > 1 ? ExamQuestionTypeEnum::MULTIPLE_CHOICE->value : ExamQuestionTypeEnum::SINGLE_CHOICE->value,
            'question' => $request->question,
        ];

        $examQuestion = $this->examQuestion->store($examQuestion);

        $now = now();
        $examQuestionOptions = [];
        foreach ($request->options as $option) {
            $examQuestionOptions[] = [
                'id' => Str::uuid(),
                'exam_question_id' => $examQuestion->id,
                'option_label' => $option['option_label'],
                'option_text' => $option['option_text'],
                'is_correct' => $option['is_correct'],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->examQuestionOption->insert($examQuestionOptions);
    }

    /**
     * logic update exam question single choice and multiple choice
     *
     * @param UpdateExamQuestionChoiceRequest $request
     * @param ExamQuestion $examQuestion
     * @return void
     */
    public function update(UpdateExamQuestionChoiceRequest $request, ExamQuestion $examQuestion): void
    {
        $options = $request->options;

        $correctCount = collect($options)
            ->where('is_correct', true)
            ->count();

        $updateQuestion = [
            'type' => $correctCount > 1 ? ExamQuestionTypeEnum::MULTIPLE_CHOICE->value : ExamQuestionTypeEnum::SINGLE_CHOICE->value,
            'question' => $request->question,
        ];

        $this->examQuestion->update($examQuestion->id, $updateQuestion);
        $this->examQuestionOption->bulkUpdateByExamQuestion($examQuestion->id, $request->options);
    }
}
