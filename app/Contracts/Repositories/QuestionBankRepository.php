<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\Eloquent\SearchInterface;
use App\Contracts\Interfaces\QuestionBankInterface;
use App\Models\QuestionBank;
use Illuminate\Http\Request;

class QuestionBankRepository extends BaseRepository implements QuestionBankInterface, SearchInterface
{
    public function __construct(QuestionBank $QuestionBank)
    {
        $this->model = $QuestionBank;
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

    public function search(Request $request): mixed
    {
        return $this->model->query()
            ->when($request->search, function ($query, $value) {
                $query->where('title','like', "%{$value}%");
            })
            ->when($request->subject_id, function ($query, $value) {
                $query->where('subject_id',$value);
            })
            ->when($request->employee_id, function ($query, $value) {
                $query->whereRelation('subject.teacherSubjects','employee_id',$value);
            })
            ->when($request->level_class_id, function ($query, $value) {
                $query->where('level_class_id',$value);
            })
            ->with([
                'subject',
                'levelClass'
            ])
            ->withCount([
                'examQuestions as total_questions' => fn($q1) => $q1->when($request->employee_id, function($query, $employee){
                    $query->where('employee_id', $employee);
                }),
                'examQuestions as total_inactive_questions' => fn($q2) => $q2->where('is_active', false)
            ])
            ->paginate($request->query('per_page', 9));
    }

    public function showDetailSearch(Request $request, string $id): QuestionBank
    {
        return $this->model->query()
            ->with([
                'subject',
                'levelClass'
            ])
            ->withCount([
                'examQuestions as total_questions' => fn($q1) => $q1->when($request->employee_id, function($query, $employee){
                    $query->where('employee_id', $employee);
                }),
                'examQuestions as total_active_questions' => fn($q2) => $q2->where('is_active', true),
                'examQuestions as total_inactive_questions' => fn($q3) => $q3->where('is_active', false)
            ])
            ->findOrFail($id);
    }

    public function loadQuestionCountByEmployee(QuestionBank $questionBank)
    {
        return $questionBank->loadCount([
            'examQuestions as total_questions' => function ($query) {
                $query->where('employee_id', auth_employee()->id);
            }
        ]);
    }
}
