<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentAttendanceResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            ...ClassroomStudentResource::make($this->classroomStudent)->toArray($request),
            'teacher_journal_id' => (int) $request->route('teacherJournal'),
            'classroom' => $this->classroomStudent->classroom->only(['id', 'name']),
            'status' => $this->status,
        ];
    }
}
