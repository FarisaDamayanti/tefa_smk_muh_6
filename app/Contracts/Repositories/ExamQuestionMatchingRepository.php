<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\Eloquent\BulkUpdateInterface;
use App\Contracts\Interfaces\Eloquent\InsertInterface;
use App\Contracts\Interfaces\ExamQuestionMatchingInterface;
use App\Models\ExamQuestionMatching;

class ExamQuestionMatchingRepository extends BaseRepository implements ExamQuestionMatchingInterface, InsertInterface, BulkUpdateInterface
{
    public function __construct(ExamQuestionMatching $ExamQuestionMatching)
    {
        $this->model = $ExamQuestionMatching;
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

    public function bulkUpdate(array $ids, array $data): mixed
    {
        return $this->model->query()
            ->whereIn('id', $ids)
            ->update($data);
    }

    public function deleteWhereId(array|string $ids): mixed
    {
        return $this->model->query()
            ->whereKey($ids)
            ->delete();
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
