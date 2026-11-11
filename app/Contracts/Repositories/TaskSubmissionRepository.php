<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\TaskSubmissionInterface;
use App\Enums\UploadDiskEnum;
use App\Models\TaskSubmission;

class TaskSubmissionRepository extends BaseRepository implements TaskSubmissionInterface
{
    public function __construct(TaskSubmission $TaskSubmission)
    {
        $this->model = $TaskSubmission;
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

    public function bulkUpdate(array $ids, array $data): mixed
    {
        return $this->model->query()->whereIn('id', $ids)->update($data);
    }

    public function getByTask(string $taskId)
    {
        return $this->model->query()->with([
            'task.topic.classroom',
            'student.user'
        ])->where('task_id', $taskId)->get();
    }


    /**
     * check name file and return boolean
     *
     * @param string $filename
     * @return bool
     */
    public function checkName(string $filename): bool
    {
        return $this->model->query()->where('answer', UploadDiskEnum::TASK_SUBMISSION->value . '/' . $filename)->exists();
    }
}
