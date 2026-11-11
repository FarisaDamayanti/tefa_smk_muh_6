<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\TaskSubmissionRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTaskSubmissionGradeRequest;
use App\Http\Requests\Teacher\StoreTaskSubmissionNoteRequest;
use App\Models\TaskSubmission;
use App\Services\Teacher\TaskSubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskSubmissionController extends Controller
{
    public function __construct
    (
        private TaskSubmissionRepository $taskSubmission,
        private TaskSubmissionService $service,
    ){}

    /**
     * update grade task submission
     * 
     * @param StoreTaskSubmissionGradeRequest $request
     * @return JsonResponse
     */
    public function update(StoreTaskSubmissionGradeRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->service->update($request);

            DB::commit();
            return ResponseHelper::success($request->validated(), message('alert.assessment_success'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponseHelper::error(message('alert.assessment_failed', $th));
        }
    }

    /**
     * update note by student id
     * 
     * @param StoreTaskSubmissionNoteRequest $request
     * @return JsonResponse
     */
    public function updateNote(StoreTaskSubmissionNoteRequest $request, TaskSubmission $taskSubmission): JsonResponse
    {
        try {
            $this->taskSubmission->update($taskSubmission->id, $request->validated());

            return ResponseHelper::success($request->validated(), message('alert.update_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }
}
