<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\TaskQuestionOptionInterface;
use App\Models\TaskQuestionOption;

class TaskQuestionOptionRepository extends BaseRepository implements TaskQuestionOptionInterface
{
    public function __construct(TaskQuestionOption $TaskQuestionOption)
    {
        $this->model = $TaskQuestionOption;
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

    public function insert(array $data): int
    {
        return $this->model->query()->insert($data);
    }
}