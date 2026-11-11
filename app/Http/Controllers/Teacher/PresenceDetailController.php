<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\Teacher\PresenceService;
use Illuminate\Http\Request;

class PresenceDetailController extends Controller
{
    public function __construct(private PresenceService $service) {}

    public function __invoke(Request $request, $teacherJournal)
    {
        try {
            $payload = $this->service->detailStudentAttendances($teacherJournal);

            // Data list
            $rows = collect($payload['data'] ?? []);

            // GET params
            $search = trim((string) $request->query('search', ''));
            $status = strtolower((string) $request->query('status', ''));

            // Filter status (present|permit|alpha)
            if ($status !== '') {
                $rows = $rows->filter(function ($row) use ($status) {
                    $rowStatus = strtolower((string) ($row['status'] ?? ''));
                    return $rowStatus === $status;
                });
            }

            // Filter search: nisn / nama / kelas
            if ($search !== '') {
                $needle = mb_strtolower($search);

                $rows = $rows->filter(function ($row) use ($needle) {
                    $nisn  = mb_strtolower((string) ($row['nisn'] ?? ''));
                    $nama  = mb_strtolower((string) ($row['student'] ?? ''));
                    $kelas = mb_strtolower((string) data_get($row, 'classroom.name', ''));

                    return str_contains($nisn, $needle)
                        || str_contains($nama, $needle)
                        || str_contains($kelas, $needle);
                });
            }

            // Balikin ke payload supaya blade tetap pakai $journal['data']
            $payload['data'] = $rows->values()->all();

            $journal = $payload;

            return view('teacher.pages.presence.detail.index', compact('journal', 'teacherJournal'));
        } catch (\Throwable $e) {
            return view('teacher.pages.presence.detail.index', [
                'journal' => ['data' => []],
                'teacherJournal' => $teacherJournal,
                'error' => $e->getMessage(),
            ]);
        }
    }   
}