<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\StudentQuestionAnswerInterface;
use App\Enums\ExamQuestionTypeEnum;
use App\Models\StudentQuestionAnswer;
use Illuminate\Support\Collection;

class StudentQuestionAnswerRepository extends BaseRepository implements StudentQuestionAnswerInterface
{
    public function __construct(StudentQuestionAnswer $StudentQuestionAnswer)
    {
        $this->model = $StudentQuestionAnswer;
    }

    public function get(): mixed
    {
        return $this->model->query()->get();
    }

    public function store(array $data): mixed
    {
        return $this->model->query()->create($data);
    }

    public function show(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id);
    }

    public function update(mixed $id, array $data): mixed
    {
        return $this->model->query()->findOrFail($id)->update($data);
    }

    public function delete(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id)->delete();
    }

    /**
     * query bulk insert for user question answers
     *
     * @param array $data
     * @return boolean
     */
    public function bulkInsert(array $data): bool
    {
        return $this->model->query()->insert($data);
    }

    /**
     * query delete data by student_exam_id
     *
     * @param string $student_exam_id
     * @return boolean
     */
    public function deleteByStudentExam(string $student_exam_id): bool
    {
        return $this->model->query()->where('student_exam_id', $student_exam_id)->delete();
    }

    /**
     * delete where in by student exam id
     *
     * @param array $student_exam_ids
     * @return bool
     */
    public function deleteWhereInByStudentExamId(array $student_exam_ids): bool
    {
        return $this->model->query()->whereIn('student_exam_id', $student_exam_ids)->delete();
    }

    /**
     * get total all score exam question type matching and essay
     *
     * @param string $student_exam_id
     * @param string $student_question_answer_id
     * @return StudentQuestionAnswer
     */
    public function getTotalScoreExamQuestion(string $student_exam_id, string $student_question_answer_id): StudentQuestionAnswer
    {
        return $this->model->query()
            ->where('student_exam_id', $student_exam_id)
            ->join('exam_questions', 'exam_questions.id', '=', 'student_question_answers.exam_question_id')
            ->whereIn('exam_questions.type', [
                ExamQuestionTypeEnum::MATCHING->value,
                ExamQuestionTypeEnum::ESSAY->value
            ])
            ->selectRaw('
                SUM(exam_questions.score) as total_score_max,
                SUM(
                    CASE 
                        WHEN student_question_answers.id != ? 
                        THEN student_question_answers.score 
                        ELSE 0 
                    END
                ) as total_score_current
            ', [$student_question_answer_id])
            ->first();
    }

    /**
     * get exam question essay not yet rated count
     *
     * @param string $student_exam_id
     * @return integer
     */
    public function getQuestionEssayNotYetRatedCount(string $student_exam_id): int
    {
        return $this->model->query()
            ->where('student_exam_id', $student_exam_id)
            ->whereRelation('examQuestion', 'type', ExamQuestionTypeEnum::ESSAY->value)
            ->whereNull('score')
            ->count();
    }

    /**
     * query get essay question by student_exam_id
     *
     * @param string $student_exam_id
     * @return Collection
     */
    public function getEssayByStudent(string $student_exam_id): Collection
    {
        return $this->model->query()
            ->with(['examQuestion'])
            ->where('student_exam_id', $student_exam_id)
            ->whereRelation('examQuestion', 'type', ExamQuestionTypeEnum::ESSAY->value)
            ->get();
    }
}
