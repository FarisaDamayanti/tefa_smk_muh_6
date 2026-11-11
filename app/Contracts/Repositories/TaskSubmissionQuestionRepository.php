<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\TaskSubmissionQuestionInterface;
use App\Models\TaskSubmissionQuestion;

class TaskSubmissionQuestionRepository extends BaseRepository implements TaskSubmissionQuestionInterface
{
    public function __construct(TaskSubmissionQuestion $TaskSubmissionQuestion)
    {
        $this->model = $TaskSubmissionQuestion;
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
}