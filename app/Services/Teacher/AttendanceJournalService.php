<?php

namespace App\Services\Teacher;

use App\Contracts\Repositories\AttendanceJournalRepository;
use App\Contracts\Repositories\Teachers\TeacherJournalRepository;
use App\Enums\AttendanceEnum;
use App\Http\Requests\Teacher\AttendanceJournalRequest;
use App\Models\TeacherJournal;
use Illuminate\Support\Facades\DB;

class AttendanceJournalService
{
    public function __construct(
        protected AttendanceJournalRepository $attendance,
        protected TeacherJournalRepository $teacherJournalRepository,
    ) {}

    public function store(AttendanceJournalRequest $request, TeacherJournal $teacherJournal): void
    {
        $data = $request->validated()['attendances'];

        $data = $this->attachTeacherJournalId($data, $teacherJournal, true);

        $this->attendance->insert($data);
    }

    public function update(AttendanceJournalRequest $request, TeacherJournal $teacherJournal): void
    {
        $data = $request->validated()['attendances'];
        $ids = [];
        $data = $this->attachTeacherJournalId($data, $teacherJournal, false, $caseStatus, $ids);

        $this->attendance->bulkUpdateById($ids, [
            'status' => DB::raw($caseStatus),
        ]);
    }

    private function attachTeacherJournalId(array $data, TeacherJournal $teacherJournal, bool $insert = false, &$caseStatus = null, &$ids = null): array
    {
        if(!$insert) {
            $caseStatus = "CASE id ";
            $result = array_map(function ($item) use ($teacherJournal, &$caseStatus, &$ids) {
                $id = $ids[] = $item['id'];
                $status = $item['status'];

                $item['teacher_journal_id'] = $teacherJournal->id;

                $caseStatus .= "WHEN {$id} THEN '{$status}' ";
                return $item;
            }, $data);
            $caseStatus .= "END";

            return $result;
        }

        $teacherJournal->loadMissing(
        'lessonSchedule.classroom.classroomStudents'
        );

        $insertedData = collect($data)->keyBy('classroom_student_id');

        $classroomStudents = $teacherJournal
            ->lessonSchedule
            ->classroom
            ->classroomStudents
            ->keyBy('id');

        return $classroomStudents->map(function ($student, $id) use ($insertedData, $teacherJournal) {
            return [
                'classroom_student_id' => $id,
                'teacher_journal_id'   => $teacherJournal->id,
                'status'               => $insertedData[$id]['status']
                    ?? AttendanceEnum::ALPHA->value,
            ];
        })->values()->toArray();
    }
}
