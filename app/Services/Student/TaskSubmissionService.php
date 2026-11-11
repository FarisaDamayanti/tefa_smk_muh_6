<?php

namespace App\Services\Student;

use App\Contracts\Repositories\TaskSubmissionRepository;
use App\Enums\StatusSubmissionTaskEnum;
use App\Enums\SubmissionTypeEnum;
use App\Enums\UploadDiskEnum;
use App\Http\Requests\Student\StoreOrUpdateTaskSubmissionRequest;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Traits\UploadTrait;

class TaskSubmissionService
{
    use UploadTrait;

    public function __construct
    (
        private TaskSubmissionRepository $taskSubmission,
    ){}

    public function generateFileName($file, $name): mixed
    {
        $filename = $name;
        $default = $filename;
        $index = 1;
        while ($this->taskSubmission->checkName($filename . '.zip')) {
            $filename = $default . '-' . $index;
            $index++;
        }

        $image = $this->uploadFile(UploadDiskEnum::TASK_SUBMISSION->value, $file, $filename, 'public');

        return $image;
    }

    /**
     * logic store task submission student
     * 
     * @param Task $task
     * @param StoreOrUpdateTaskSubmissionRequest $request
     * @return array|bool
     */
    public function store(Task $task, StoreOrUpdateTaskSubmissionRequest $request): array|bool
    {
        $isLate = now()->isAfter($task->deadline);
        if (!$isLate || $task->allow_overdue) {
            $data = [
                'status' => $isLate ? StatusSubmissionTaskEnum::OVERDUE->value : StatusSubmissionTaskEnum::SUBMITTED->value,
                'submit_date' => now(),
                'task_id' => $task->id,
                'student_id' => auth_student()->id,
            ];

            if ($task->submission_type === SubmissionTypeEnum::FILE) {
                $filename = auth_user()->slug . '-' . str($task->title)->slug();
                $data['answer'] = $this->generateFileName($request->file('answer'), $filename);
            } else {
                $data['answer'] = $request->answer;
            }

            return $data;
        } else {
            return false;
        }
    }

    /**
     * logic update task submission student
     * 
     * @param Task $task
     * @param TaskSubmission $taskSubmission
     * @param StoreOrUpdateTaskSubmissionRequest $request
     * @return array|bool
     */
    public function update(Task $task, TaskSubmission $taskSubmission, StoreOrUpdateTaskSubmissionRequest $request): array|bool
    {
        $isLate = now()->isAfter($task->deadline);
        if (!$isLate || $task->allow_overdue) {
            $data = [
                'status' => $isLate ? StatusSubmissionTaskEnum::OVERDUE->value : StatusSubmissionTaskEnum::SUBMITTED->value,
                'submit_date' => now(),
            ];

            $this->remove($taskSubmission->answer);

            if ($task->submission_type === SubmissionTypeEnum::FILE) {
                $filename = auth_user()->slug . '-' . str($task->title)->slug();
                $data['answer'] = $this->generateFileName($request->file('answer'), $filename);
            } else {
                $data['answer'] = $request->answer;
            }

            return $data;
        } else {
            return false;
        }
    }
}
