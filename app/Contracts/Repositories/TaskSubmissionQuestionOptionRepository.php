<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\TaskSubmissionQuestionOptionInterface;
use App\Models\TaskSubmissionQuestionOption;

class TaskSubmissionQuestionOptionRepository extends BaseRepository implements TaskSubmissionQuestionOptionInterface
{
    public function __construct(TaskSubmissionQuestionOption $TaskSubmissionQuestionOption)
    {
        $this->model = $TaskSubmissionQuestionOption;
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