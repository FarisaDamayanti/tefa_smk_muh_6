<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Contracts\Repositories\TaskRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreTaskRequest;
use App\Http\Requests\Teacher\UpdateTaskRequest;
use App\Http\Resources\Teacher\TaskDetailResource;
use App\Models\Task;
use App\Models\Topic;
use App\Models\User;
use App\Services\Teacher\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function __construct
    (
        private TaskRepository $task,
        private ClassroomStudentRepository $classroomStudent,
        private TaskService $service,
    ){}

    /**
     * create task data
     *
     * @param StoreTaskRequest $request
     * @param Topic $topic
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request, Topic $topic): JsonResponse
    {
        try {
            $data = $this->service->store($request, $topic);

            return ResponseHelper::success($data, message('alert.add_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * show detail task
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Request $request, Task $task): JsonResponse
    {
        try {
            $classroomStudents = $this->classroomStudent->getByClassId($task->topic->classroom_id);
            $classroomStudents->loadMissing(['student.taskSubmissions', 'classroom']);
            $task->classroomStudents = $this->service->mappingData($task, $classroomStudents, $request);
            $data = TaskDetailResource::make($task);

            return ResponseHelper::success($data, message('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::success(message('alert.fetch_failed', $th));
        }
    }

    /**
     * update task data
     *
     * @param Task $task
     * @param UpdateTaskRequest $request
     * @return JsonResponse
     */
    public function update(Task $task, UpdateTaskRequest $request): JsonResponse
    {
        try {
            $this->task->update($task->id, $request->validated());

            return ResponseHelper::success($request->validated(), message('alert.update_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }

    /**
     * delete task data
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        try {
            $taskSubmissions_count = $task->taskSubmissions->count();

            if ($taskSubmissions_count <= 0) {
                $this->task->delete($task->id);
                return ResponseHelper::success(null, message('alert.delete_success'));
            } else {
                return ResponseHelper::success(null, message('alert.delete_constrained', 'Terdapat ' . $taskSubmissions_count . ' siswa yang sudah mengumpulkan'), Response::HTTP_CONFLICT);
            }
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.delete_failed', $th));
        }
    }
}
