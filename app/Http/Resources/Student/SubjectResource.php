<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
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
            'name' => $this->name,
            'teacher_name' => $this->teacher_name,
            'classroom_id' => $this->classroom_id,
            'classroom_name' => $this->classroom_name,
            'school_year' => $this->school_year,
            'level_class' => $this->level_class,
            'subject_name' => $this->subject_name,
            'subject_id' => $this->subject_id,
            'religion_name' => $this->religion_name,
        ];
    }
}
