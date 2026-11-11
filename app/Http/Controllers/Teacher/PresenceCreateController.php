<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\Teacher\PresenceService; // kalau kamu sudah punya service ini

class PresenceCreateController extends Controller
{
    public function __construct(private PresenceService $service)
    {
    }

    public function __invoke($teacherJournal)
    {
        try {
            $payload = $this->service->detailStudentAttendances($teacherJournal);

            // karena 'data' itu array list
            $students = $payload['data'] ?? [];

            return view('teacher.pages.presence.create', compact('teacherJournal', 'students'));
        } catch (\Throwable $e) {
            return view('teacher.pages.presence.create', [
                'teacherJournal' => $teacherJournal,
                'students' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

}
