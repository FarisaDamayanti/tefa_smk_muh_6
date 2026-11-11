<?php

namespace App\Http\Resources\Teacher;

use App\Enums\SubmissionStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * This is for TaskSubmission
 */
class EvaluationResource extends JsonResource
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
            'student' => $this->student->user->name,
            'grade' => $this->grade,
            'classroom' => $this->task->topic->classroom,
            'status' => SubmissionStatusEnum::compute(
                $this->status,
                $this->task->allow_overdue,
                $this->grade
            )
        ];
    }
}
