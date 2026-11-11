<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\AttendanceRepository;
use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Contracts\Repositories\Teachers\TeacherJournalRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\AttendanceJournalRequest;
use App\Http\Resources\StudentAttendanceResource;
use App\Models\TeacherJournal;
use App\Services\Teacher\AttendanceJournalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AttendanceJournalController extends Controller
{
    public function __construct(
        protected AttendanceJournalService $service,
        protected TeacherJournalRepository $teacherJournalRepository,
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttendanceJournalRequest $request, TeacherJournal $teacherJournal)
    {
        DB::beginTransaction();
        try {
            $this->service->store($request, $teacherJournal);
            DB::commit();
            return ResponseHelper::success(message('alert.add_success'));
        } catch (Throwable $th){
            DB::rollback();
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $teacherJournal)
    {
        try {
            $data = $this->teacherJournalRepository->getStudentAttendance($teacherJournal);

            return ResponseHelper::success(StudentAttendanceResource::collection($data->attendanceJournals), message('alert.fetch_success'));
        } catch (Throwable $th){
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttendanceJournalRequest $request, TeacherJournal $teacherJournal)
    {
        DB::beginTransaction();
        try {
            $this->service->update($request, $teacherJournal);
            DB::commit();
            return ResponseHelper::success(message('alert.update_success'));
        } catch (Throwable $th){
            DB::rollback();
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }
}
