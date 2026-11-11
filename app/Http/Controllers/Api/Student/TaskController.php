<?php

namespace App\Http\Controllers\Api\Student;

use App\Contracts\Repositories\TaskRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Student\TaskDetailResource;
use App\Http\Resources\Student\TaskResource;
use App\Models\Classroom;
use App\Models\Task;
use App\Services\Student\TaskService;
use App\Traits\PaginationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use PaginationTrait;

    public function __construct
    (
        private TaskRepository $task,
        private TaskService $service
    ){}

    /**
     * get all data task student
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $rawData = $this->task->getAllTaskStudent(auth_student()->id, $request);
            $mapped = $this->service->index($rawData);
            $data['data'] = TaskResource::collection($rawData);
            $data['paginate'] = $this->customPaginate($mapped->currentPage(), $mapped->lastPage(), $mapped->perPage());

            return ResponseHelper::success($data, message('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }

    /**
     * show detail Task and submission user logged in
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task): JsonResponse
    {
        try {
            $data = TaskDetailResource::make($task);

            return ResponseHelper::success($data, message('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }

    /**
     * get count data for statistik
     *
     * @return JsonResponse
     */
    public function statistic(): JsonResponse
    {
        try {
            $tasks = $this->task->getWithRelation(auth_student()->id);
            return ResponseHelper::success($this->service->createStatistic($tasks), message('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }

    }

    /**
     * get latest student task
     * 
     * @return JsonResponse
     */
    public function getLatestStudentTasks(): JsonResponse
    {
        try {
            $classroom_id = auth_student()->currentStudentClassroom->classroom->id;
            $tasks = $this->task->getLatestTask($classroom_id, 5);
            
            $data = TaskResource::collection($tasks);
            return ResponseHelper::success($data, message('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }
}
