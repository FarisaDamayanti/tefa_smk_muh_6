<?php

namespace App\Services\Teacher;

use App\Contracts\Repositories\ExamQuestionMatchingAnswerRepository;
use App\Contracts\Repositories\ExamQuestionRepository;
use App\Contracts\Repositories\ExamQuestionMatchingRepository;
use App\Enums\ExamQuestionTypeEnum;
use App\Http\Requests\Teacher\StoreExamQuestionMatchingRequest;
use App\Http\Requests\Teacher\UpdateExamQuestionMatchingRequest;
use App\Models\ExamQuestion;
use App\Models\QuestionBank;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExamQuestionMatchingService
{
    public function __construct(
        private ExamQuestionRepository $examQuestion,
        private ExamQuestionMatchingRepository $examQuestionMatching,
        private ExamQuestionMatchingAnswerRepository $examQuestionMatchingAnswer,

        private ExamQuestionService $service
    ) {}

    public function store(StoreExamQuestionMatchingRequest $request, QuestionBank $questionBank): void
    {
        $this->service->canAdd($questionBank, 1);

        $data = $request->validated();
        $examQuestionMatchingInsert = [];
        $examQuestionMatchingAnswerInsert = [];
        $questionBankId = $questionBank->id;
        $employeeId = auth_employee()->id;

        $examQuestion = $this->examQuestion->store([
            'employee_id' => $employeeId,
            'question_bank_id' => $questionBankId,
            'question' => $data['question'],
            'score' => $data['score'],
            'type' => ExamQuestionTypeEnum::MATCHING->value
        ]);

        foreach($data['questions'] as &$item)
        {
            foreach($item as $index => &$subitem)
            {
                $newItem = [
                    'id' => Str::uuid(),
                    'item_text' => $subitem,
                    'group' => $index == 0 ? 'left' : 'right',
                    'exam_question_id' => $examQuestion->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $examQuestionMatchingInsert[] = $newItem;
                $subitem = $newItem;
            }

            $examQuestionMatchingAnswerInsert[] = [
                'id' => Str::uuid(),
                'left_item_id' => $item[0]['id'],
                'right_item_id' => $item[1]['id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $this->examQuestionMatching->insert($examQuestionMatchingInsert);
        $this->examQuestionMatchingAnswer->insert($examQuestionMatchingAnswerInsert);
    }

    public function update(UpdateExamQuestionMatchingRequest $request, string $examQuestion)
    {
        $isNew = false;
        $data = $request->validated();
        $examQuestionMatching = [];
        $examQuestionMatchingAnswer = [];

        $this->examQuestion->update($examQuestion, ['question' => $data['question'], 'score' => $data['score']]);

        foreach($data['questions'] as $index => &$matching) {
            foreach ($matching as $matchIndex => &$match) {
                if (!isset($match['id'])) {
                    $match['id'] = Str::uuid();
                    $isNew = true;
                }

                $match['group'] = $matchIndex == 0 ? 'left' : 'right';
                $match['exam_question_id'] = $examQuestion;

                $examQuestionMatching[] = $match;
            }

            if($isNew){
                $examQuestionMatchingAnswer[] = [
                    'id' => Str::uuid(),
                    'left_item_id' => $matching[0]['id'],
                    'right_item_id' => $matching[1]['id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $isNew = false;
            }
        }

        $this->examQuestionMatching->upsert($examQuestionMatching, ['id'], ['item_text']);
        if(!empty($examQuestionMatchingAnswer)){
            $this->examQuestionMatchingAnswer->insert($examQuestionMatchingAnswer);
        }

        if(!empty($data['delete'])){
            $this->examQuestionMatching->deleteWhereId($data['delete']);
        }

    }
}
