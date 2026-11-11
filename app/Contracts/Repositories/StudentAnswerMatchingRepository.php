<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\StudentAnswerMatchingInterface;
use App\Models\StudentAnswerMatching;

class StudentAnswerMatchingRepository extends BaseRepository implements StudentAnswerMatchingInterface
{
    public function __construct(StudentAnswerMatching $StudentAnswerMatching)
    {
        $this->model = $StudentAnswerMatching;
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