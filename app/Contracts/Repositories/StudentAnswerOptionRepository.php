<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\StudentAnswerOptionInterface;
use App\Models\StudentAnswerOption;

class StudentAnswerOptionRepository extends BaseRepository implements StudentAnswerOptionInterface
{
    public function __construct(StudentAnswerOption $StudentAnswerOption)
    {
        $this->model = $StudentAnswerOption;
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
     * query bulk insert
     *
     * @param array $data
     * @return boolean
     */
    public function bulkInsert(array $data): bool
    {
        return $this->model->query()->insert($data);
    }
}