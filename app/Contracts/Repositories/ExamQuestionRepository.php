<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\ExamQuestionInterface;
use App\Contracts\Interfaces\Eloquent\BulkUpdateInterface;
use App\Contracts\Interfaces\Eloquent\SearchInterface;
use App\Enums\ExamQuestionTypeEnum;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ExamQuestionRepository extends BaseRepository implements ExamQuestionInterface, BulkUpdateInterface, SearchInterface
{
    public function __construct(ExamQuestion $ExamQuestion)
    {
        $this->model = $ExamQuestion;
    }

    public function get(): mixed
    {
        return $this->model->query()->get();
    }

    public function store(array $data): mixed
    {
        return $this->model->query()->create($data);
    }

    /**
     * Insert
     *
     * @param array[] $data
     * @return mixed
     */
    public function insert(array $data): mixed
    {
        return $this->model->query()->insert($data);
    }

    public function upsert(array $values, array $uniqueBy, array $update)
    {
        return $this->model->query()->upsert($values, $uniqueBy, $update);
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
     * get counting by filter subject_id, level_class_id, school_year_id, and semester
     *
     * @param int $subject_id
     * @param int $level_class_id
     * @param int $school_year_id
     * @param string $semester
     * @return int
     */
    public function getCountWithFilter(int $subject_id, int $level_class_id, int $school_year_id, string $semester): int
    {
        return $this->model->query()
            ->where('subject_id', $subject_id)
            ->where('level_class_id', $level_class_id)
            ->where('school_year_id', $school_year_id)
            ->where('semester', $semester)
            ->count();
    }

    public function updateOrCreate(array $attribute, array $data){
        return $this->model->query()
            ->updateOrCreate($attribute, $data);
    }

    public function bulkUpdate(array $ids, array $data): mixed
    {
        return $this->model->query()
            ->whereIn('id', $ids)
            ->update($data);
    }

    public function getAllByQuestionBank(string $questionBank): Collection
    {
        return $this->model->query()
            ->where('question_bank_id', $questionBank)
            ->get();
    }

    public function getByQuestionBankPaginated(string $questionBank): LengthAwarePaginator
    {
        return $this->model->query()
            ->where('question_bank_id', $questionBank)
            ->latest()
            ->paginate(10);
    }

    public function search(Request $request): mixed
    {
        return $this->model->query()
            ->select([
                'exam_questions.*',
                'users.name as teacher_name'
            ])
            ->join('employees', 'employees.id', '=', 'exam_questions.employee_id')
            ->join('users', 'users.id', '=', 'employees.user_id')
            ->when(
                $request->search,
                fn($q, $search) =>
                $q->where('exam_questions.question', 'like', "%{$search}%")
            )
            ->when(
                $request->question_bank_id,
                fn($q, $id) =>
                $q->where('exam_questions.question_bank_id', $id)
            )
            ->when(
                $request->employee_id,
                fn($q, $id) =>
                $q->where('exam_questions.employee_id', $id)
            )
            ->latest('exam_questions.created_at')
            ->with([
                'sortExamQuestionOptions',
                'leftExamQuestionMatchings.leftItemAnswer',
                'rightExamQuestionMatchings'
            ])
            ->paginate($request->query('per_page', 10));
    }

    public function searchGet(Request $request): Collection
    {
        return $this->model->query()
            ->select([
                'exam_questions.*',
                'users.name as teacher_name'
            ])
            ->join('employees', 'employees.id', '=', 'exam_questions.employee_id')
            ->join('users', 'users.id', '=', 'employees.user_id')
            ->when(
                $request->search,
                fn($q, $search) =>
                $q->where('exam_questions.question', 'like', "%{$search}%")
            )
            ->when(
                $request->question_bank_id,
                fn($q, $id) =>
                $q->where('exam_questions.question_bank_id', $id)
            )
            ->when(
                $request->employee_id,
                fn($q, $id) =>
                $q->where('exam_questions.employee_id', $id)
            )
            ->with([
                'sortExamQuestionOptions',
                'leftExamQuestionMatchings.leftItemAnswer',
                'rightExamQuestionMatchings',
            ])
            ->latest('exam_questions.created_at')
            ->get();
    }


    public function getTotalSubmittedQuestions(string $questionBankId, string $employeeId): int
    {
        return $this->model->query()
            ->where('question_bank_id', $questionBankId)
            ->where('employee_id', $employeeId)
            ->count();
    }

    /**
     * get question by question bank and random order
     *
     * @param string $question_bank_id
     * @param integer $limit_question
     * @return Collection
     */
    public function getByQuestionBankRandomOrder(string $question_bank_id, int $limit_question): Collection
    {
        return $this->model->query()
            ->with(['randomQuestionOptions', 'rightExamQuestionMatchingsRandomized', 'leftExamQuestionMatchingsRandomized'])
            ->where('question_bank_id', $question_bank_id)
            ->where('is_active', 1)
            ->inRandomOrder()
            ->limit($limit_question)
            ->select(['id', 'type', 'question'])
            ->get();
    }

    /**
     * get answer where in and pluck with map key question
     *
     * @param array $question_ids
     * @return Collection
     */
    public function getAnswerwhereIn(array $question_ids): Collection
    {
        return $this->model->query()
            ->with([
                'sortExamQuestionOptions' => function ($query) {
                    $query->where('is_correct', true)->select('id', 'exam_question_id');
                },
                'rightExamQuestionMatchingsRandomized.rightItemAnswer' => function ($query) {
                    $query->select('id', 'left_item_id', 'right_item_id');
                },
            ])
            ->whereIn('id', $question_ids)
            ->select('id', 'type', 'score')
            ->get()
            ->keyBy('id');
    }

    /**
     * Determine are question in given id has been used or not
     *
     * @param array|string $id
     * @return boolean
     */
    public function hasUsed(array|string $id): bool
    {
        return $this->model
            ->whereKey($id)
            ->where(function($q){
                $q->where('is_active', 1)
                ->orWhereHas('studentQuestionAnswers');
            })
            ->exists();
    }

    /**
     * Get all record in given id with relations
     *
     * @param array $ids
     * @return Collection
     */
    public function whereInGetWith(array $ids): Collection
    {
        return $this->model->query()
            ->whereKey($ids)
            ->with([
                'sortExamQuestionOptions',
                'leftExamQuestionMatchings.leftItemAnswer',
                'rightExamQuestionMatchings',
            ])
            ->get();
    }
}
