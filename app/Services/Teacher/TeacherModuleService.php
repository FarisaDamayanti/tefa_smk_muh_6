<?php

namespace App\Services\Teacher;

use App\Models\Module;

class TeacherModuleService
{
    public function store(array $data): bool
    {
        Module::create([
            'title' => $data['title'],
            'meeting_number' => $data['meeting_number'],
            'content' => $data['content'] ?? null,
            'link' => $data['link'] ?? null,
            'subject_id' => $data['subject_id'],
            'classroom_id' => $data['classroom_id'],
            'employee_id' => auth()->id(),
        ]);

        return true;
    }


    public function getAll(): array
    {
        return Module::select(
            'id',
            'title',
            'meeting_number',
            'link',
            'content'
        )
            ->orderBy('meeting_number')
            ->get()
            ->toArray();
    }
}
