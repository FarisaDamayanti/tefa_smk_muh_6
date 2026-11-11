<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'deadline' => $this->deadline->translatedFormat('d F Y H.i'),
            'subject_name' => $this->topic->subject->name,
            'school_year' => $this->topic->classroom->schoolYear->school_year,
            'semester' => $this->topic->semester,
            'status' => $this->status,
        ];
    }
}
