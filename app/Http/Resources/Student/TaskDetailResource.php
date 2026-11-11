<?php

namespace App\Http\Resources\Student;

use App\Http\Resources\Student\TaskSubmissionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'submission_type' => $this->submission_type,
            'allow_overdue' => $this->allow_overdue,
            'meeting_number' => $this->meeting_number,
            'semester' => $this->semester,
            'is_submitted' => $this->currentUserTaskSubmission ? true : false,
            'user_task_submission' => TaskSubmissionResource::make($this->currentUserTaskSubmission),
        ];
    }
}
