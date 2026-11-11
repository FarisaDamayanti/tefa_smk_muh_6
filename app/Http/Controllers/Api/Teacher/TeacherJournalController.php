<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\LessonScheduleRepository;
use App\Contracts\Repositories\Teachers\TeacherJournalRepository;
use App\Helpers\ResponseHelper;
use App\Helpers\SemesterHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTeacherJournalRequest;
use App\Http\Requests\Teacher\UpdateTeacherJournalRequest;
use App\Http\Resources\MeetingResource;
use App\Models\Classroom;
use App\Models\LessonSchedule;
use App\Models\Subject;
use App\Models\TeacherJournal;
use App\Services\Teacher\TeacherJournalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherJournalController extends Controller
{
    public function __construct
    (
        private TeacherJournalRepository $teacherJournal,
        private LessonScheduleRepository $lessonSchedule,
        private TeacherJournalService $service,
    ){}

    /**
     * API for store teacher journal
     *
     * @param LessonSchedule $lessonSchedule
     * @param StoreTeacherJournalRequest $request
     * @param int $meeting
     * @return JsonResponse
     */
    public function store(LessonSchedule $lessonSchedule, StoreTeacherJournalRequest $request, int $meeting): JsonResponse
    {
        $weeklyScheduleCount = $this->lessonSchedule->getSameScheduleCount(auth_employee()->id, $lessonSchedule->teacherSubject->subject_id, $lessonSchedule->school_year_id);
        if ($meeting > ($weeklyScheduleCount * 16) || $meeting < 1) {
            return ResponseHelper::error(trans('alert.add_failed') . '. Pertemuan tidak valid');
        }

        try {
            $data = $this->service->storeMeeting($lessonSchedule, $request, $meeting);
            $this->teacherJournal->store($data);

            return ResponseHelper::success($data, message('alert.add_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * API for update teacher journal
     *
     * @param TeacherJournal $teacherJournal
     * @param UpdateTeacherJournalRequest $request
     * @return JsonResponse
     */
    public function update(TeacherJournal $teacherJournal, UpdateTeacherJournalRequest $request): JsonResponse
    {
        try {
            $this->teacherJournal->update($teacherJournal->id, $request->validated());

            return ResponseHelper::success($request->validated(), message('alert.update_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }

    /**
     * Get classroom meeting by subject with semester filter
     *
     * @param Request $request
     * @param Classroom $classroom
     * @param Subject $subject
     * @return JsonResponse
     */
    public function getMeetings(Request $request, Classroom $classroom, Subject $subject): JsonResponse
    {
        try {
            $meetings = $this->teacherJournal->getByClassroomAndSubject(
                $classroom->id,
                $subject->id,
                $request->query('semester', SemesterHelper::getCurrentSemester()),
                $request
            );

            $data = MeetingResource::collection($meetings)->toArray($request);
            $weeklyScheduleCount = $this->lessonSchedule->getSameScheduleCount(auth_user()->id, $subject->id, $classroom->school_year_id);
            
            $data = $this->service->index($data, $weeklyScheduleCount, $request);

            return ResponseHelper::success($data, message('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }
}
