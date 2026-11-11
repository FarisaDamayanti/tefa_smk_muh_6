<?php

namespace App\Services\Teacher;

use App\Contracts\Interfaces\Teachers\TeacherJournalInterface;
use App\Enums\SemesterEnum;
use App\Http\Requests\StoreTeacherJournalRequest;
use App\Http\Requests\Teacher\StoreTeacherJournalRequest as TeacherStoreTeacherJournalRequest;
use App\Http\Requests\UpdateTeacherJournalRequest;
use App\Models\LessonSchedule;
use Illuminate\Http\Request;

class TeacherJournalService
{
    /**
     * mapping data for meeting
     * 
     * @param array $meetings
     * @param int $weeklyScheduleCount
     * @return array
     */
    public function index(array $meetings, int $weeklyScheduleCount, Request $request): array
    {
        $totalMeetings = $weeklyScheduleCount * 16;
        $data = [];

        if(!$request->filled('search') && !$request->filled('date')) {
            for($i = 1; $i <= $totalMeetings; $i++) {
                $data[$i] = [
                    'id' => null,
                    'title' => null,
                    'description' => null,
                    'date' => null,
                    'meeting' => $i,
                    'semester' => null,
                    'modules' => null,
                    'tasks' => null,
                    'lesson_schedule_id' => null,
                ];
            }
        }

        foreach($meetings as $meeting) {
            $meetingNumber = $meeting['meeting'] ?? null;
            
            if($meetingNumber && is_numeric($meetingNumber) && $meetingNumber <= $totalMeetings && $meetingNumber > 0) {
                $data[$meetingNumber] = $meeting;
            }
        }

        return $data;
    }
    
    /**t
     * store teacher journal for meeting
     * 
     * @param TeacherStoreJournalTeacherRequest $request
     * @param int $meeting
     * @return array
     */
    public function storeMeeting(LessonSchedule $lessonSchedule, TeacherStoreTeacherJournalRequest $request, int $meeting): array
    {
        $data = $request->validated();

        $semester = (now()->month >= 1 && now()->month <= 6) ? SemesterEnum::GENAP->value : SemesterEnum::GANJIL->value;

        return [
            'title' => $data['title'],
            'lesson_schedule_id' => $lessonSchedule->id,
            'description' => $data['description'],
            'meeting' => $meeting,
            'date' => $data['date'],
            'semester' => $semester,
        ];
    }

    public function store(StoreTeacherJournalRequest $request, LessonSchedule $lessonSchedule): array|bool
    {
        $data = $request->validated();
        return [
            'title' => $data['title'],
            'lesson_schedule_id' => $lessonSchedule->id,
            'description' => $data['description'],
            'date' => now(),
        ];
    }

    public function update(updateTeacherJournalRequest $request, LessonSchedule $lessonSchedule): array|bool
    {
        $data = $request->validated();
        return [
            'title' => $data['title'],
            'lesson_schedule_id' => $lessonSchedule->id,
            'description' => $data['description'],
        ];
    }

    /**
     * checkDuplicatedStudent
     *
     * @param  TeacherJournalRequest $request
     * @return array
     */
    public function checkDuplicatedStudent(StoreTeacherJournalRequest $request) : array|bool
    {
        $data = $request->validated();
        $students = [];
        if($request->sick) $students = array_merge($students,$data['sick']);
        if($request->alpha) $students = array_merge($students,$data['alpha']);
        if($request->permission) $students = array_merge($students,$data['permission']);
        $uniqueStudents = array_unique($students);
        $duplicates = array_diff_assoc($students, $uniqueStudents);
        return $duplicates;
    }

    /**
     * checkDuplicatedStudent
     *
     * @param  TeacherJournalRequest $request
     * @return array
     */
    public function checkDuplicatedStudentUpdate(UpdateTeacherJournalRequest $request) : array|bool
    {
        $data = $request->validated();
        $students = [];
        if($request->sick) $students = array_merge($students,$data['sick']);
        if($request->alpha) $students = array_merge($students,$data['alpha']);
        if($request->permission) $students = array_merge($students,$data['permission']);
        $uniqueStudents = array_unique($students);
        $duplicates = array_diff_assoc($students, $uniqueStudents);
        return $duplicates;
    }
}
