<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\StudentExamInterface;
use App\Enums\ExamQuestionTypeEnum;
use App\Models\StudentExam;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StudentExamRepository extends BaseRepository implements StudentExamInterface
{
    public function __construct(StudentExam $StudentExam)
    {
        $this->model = $StudentExam;
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
     * search query
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request): mixed
    {
        return $this->model->query()
            ->when($request->search, function($query, $search)  {
                $query->whereRelation('student.user', 'name', 'like', "%$search%");
            })
            ->when($request->classroom_id, function($query, $classroom_id) {
                $query->whereRelation('exam.questionBank', 'classroom_id', $classroom_id);
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->paginate($request->query('per_page', 10));
    }

    public function hasUsedOnExam(string $question_bank_id): bool
    {
        return $this->model->query()
            ->whereRelation('exam', 'question_bank_id', $question_bank_id)
            ->exists();
    }

    /**
     * query get first or if there isn't then create
     *
     * @param array $attributes
     * @param array $values
     * @return StudentExam
     */
    public function firstOrCreate(array $attributes, array $values): StudentExam
    {
        return $this->model->query()->firstOrCreate($attributes, $values);
    }

    /**
     * delete where in by id
     *
     * @param array $ids
     * @return bool
     */
    public function deleteWhereInById(array $ids): bool
    {
        return $this->model->query()->whereIn('id', $ids)->delete();
    }

    /**
     * get where in by id
     *
     * @param array $ids
     * @return Collection
     */
    public function whereInById(array $ids): Collection
    {
        return $this->model->query()->whereIn('id', $ids)->get();
    }

    public function showResult(string $id)
    {
        return $this->model->query()
            ->with([
                'studentQuestionAnswers.examQuestion.sortExamQuestionOptions',
                'studentQuestionAnswers.examQuestion.leftExamQuestionMatchings.leftItemAnswer',
                'studentQuestionAnswers.examQuestion.correctQuestionOptions',
                'studentQuestionAnswers.studentAnswerOptions',
                'studentQuestionAnswers.studentAnswerMatchings.leftExamQuestionMatching',
                'studentQuestionAnswers.studentAnswerMatchings.rightExamQuestionMatching',
                'studentQuestionAnswers.employee.user',
                'student.user',
                'student.currentStudentClassroom.classroom',
                'exam',
            ])
            ->withCount([
                'studentQuestionAnswers as correct_answer_count' => fn($query) => $query->where('is_correct', 1),
                'studentQuestionAnswers as total_questions_count',
            ])
            ->findOrFail($id);
    }

    /**
     * get single choice dan multiple choice count
     *
     * @param string $id
     * @return integer
     */
    public function getSingleAndMultipleChoiceCount(string $id): int
    {
        return $this->model->query()
            ->findOrFail($id)
            ->studentQuestionAnswers()
            ->where(function($query) {
                $query->whereRelation('examQuestion', 'type', ExamQuestionTypeEnum::SINGLE_CHOICE->value)
                    ->orWhereRelation('examQuestion', 'type', ExamQuestionTypeEnum::MULTIPLE_CHOICE->value);
            })
            ->count();
    }
}
