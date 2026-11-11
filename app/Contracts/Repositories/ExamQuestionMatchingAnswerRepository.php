<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\Eloquent\InsertInterface;
use App\Contracts\Interfaces\ExamQuestionMatchingAnswerInterface;
use App\Models\ExamQuestionMatchingAnswer;

class ExamQuestionMatchingAnswerRepository extends BaseRepository implements ExamQuestionMatchingAnswerInterface, InsertInterface
{
    public function __construct(ExamQuestionMatchingAnswer $ExamQuestionMatchingAnswer)
    {
        $this->model = $ExamQuestionMatchingAnswer;
    }

    public function get(): mixed
    {
        return $this->model->query()->get();
    }

    public function insert(array $data): int
    {
        return $this->model->query()->insert($data);
    }

    public function upsert($values, $uniqueBy, $update): int
    {
        return $this->model->query()->upsert($values, $uniqueBy, $update);
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
