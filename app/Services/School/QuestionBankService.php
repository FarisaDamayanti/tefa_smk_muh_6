<?php

namespace App\Services\School;

use App\Contracts\Repositories\ExamRepository;
use App\Contracts\Repositories\QuestionBankRepository;
use App\Contracts\Repositories\StudentExamRepository;
use App\Http\Requests\School\QuestionBankRequest;
use Exception;

class QuestionBankService
{
    public function __construct(
        private QuestionBankRepository $questionBank,
        private ExamRepository $examRepository,
        private StudentExamRepository $studentExam
    ) {}

    public function update(QuestionBankRequest $request, string $id): void
    {
        $data = $request->validated();
        $model = $this->questionBank->show($id);

        if(isset($data['question_limit'])){
            $model->load([
                'exams' => fn($q) => $q->where('exam_date', '>=', now())
            ]);

            $examsIds = $model->exams->pluck('id');
            if($examsIds->isNotEmpty()){
                $this->examRepository->bulkUpdate($examsIds->toArray(), ['total_questions' => $request->question_limit]);
            }
        }

        $model->update($data);
    }

    public function delete(string $id): mixed
    {
        if(! $this->studentExam->hasUsedOnExam($id)){
            return $this->questionBank->delete($id);
        }

        throw new Exception('Bank soal sudah digunakan pada ujian.');
    }
}
