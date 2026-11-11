<?php

namespace App\Http\Controllers\Api\Student;

use App\Contracts\Repositories\TaskSubmissionRepository;
use App\Enums\SubmissionTypeEnum;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreOrUpdateTaskSubmissionRequest;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Services\Student\TaskSubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskSubmissionController extends Controller
{
    public function __construct
    (
        private TaskSubmissionRepository $taskSubmission,
        private TaskSubmissionService $service,
    ){}

    /**
     * store or update task submission student
     * 
     * @param StoreOrUpdateTaskSubmissionRequest $request
     * @param Task $task
     * @param ?TaskSubmission $taskSubmission
     * @return JsonResponse
     */
    public function storeOrUpdate(StoreOrUpdateTaskSubmissionRequest $request, Task $task): JsonResponse
    {
        try {
            $data = false;

            $currentUserTaskSubmission = $task->currentUserTaskSubmission;
            if($currentUserTaskSubmission){
                $data = $this->service->update($task, $currentUserTaskSubmission, $request);
            } else{
                $data = $this->service->store($task, $request);
            }

            if ($data) {
                $currentUserTaskSubmission
                    ? $this->taskSubmission->update($currentUserTaskSubmission->id, $data)
                    : $this->taskSubmission->store($data);

                return ResponseHelper::success($data, message('alert.submit_success'));
            } else {
                return ResponseHelper::success($data, message('alert.submit_failed', 'Sudah melebihi deadline'), Response::HTTP_FORBIDDEN);
            }
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.submit_failed', $th));
        }
    }
}
