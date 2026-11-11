<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\TaskQuestionInterface;
use App\Models\TaskQuestion;

class TaskQuestionRepository extends BaseRepository implements TaskQuestionInterface
{
    public function __construct(TaskQuestion $TaskQuestion)
    {
        $this->model = $TaskQuestion;
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

    public function getByTaskRandomOrder(string $task_id)
    {
        return $this->model->query()
            ->with(['randomTaskQuestionOptions'])
            ->where('task_id', $task_id)
            ->inRandomOrder()
            ->select(['id', 'type', 'question'])
            ->get();
    }
}