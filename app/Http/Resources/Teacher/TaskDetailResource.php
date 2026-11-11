<?php

namespace App\Http\Resources\Teacher;

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
            'deadline' => $this->deadline->translatedFormat('d F Y H.i'),
            'submission_type' => $this->submission_type,
            'allow_overdue' => $this->allow_overdue,
            'semester' => $this->semester,
            'task_submissions' => TaskSubmissionResource::collection($this->classroomStudents),
        ];
    }
}
