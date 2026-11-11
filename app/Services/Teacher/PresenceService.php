<?php

namespace App\Services\Teacher;

use App\Http\Controllers\Api\Teacher\AttendanceJournalController as TeacherAttendanceJournalApiController;
use Illuminate\Http\JsonResponse;

class PresenceService
{
    public function detailStudentAttendances($teacherJournal): array
    {
        /** @var JsonResponse $response */
        $response = app(TeacherAttendanceJournalApiController::class)->show($teacherJournal);

        // JsonResponse -> array
        $payload = $response->getData(true);

        // validasi meta.code
        if (($payload['meta']['code'] ?? 500) !== 200) {
            throw new \Exception($payload['meta']['message'] ?? 'Gagal fetch data');
        }

        return $payload;
    }
}