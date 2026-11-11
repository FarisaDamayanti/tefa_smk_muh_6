<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Contracts\Repositories\TaskSubmissionRepository;
use App\Contracts\Repositories\TopicRepository;
use App\Enums\SubmissionTypeEnum;
use App\Exports\Evaluation\TaskRecapReportExport;
use App\Exports\TaskAnswersExport;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\EvaluationRequest;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Task;
use App\Services\Teacher\EvaluationService;
use App\Traits\PaginationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;

class EvaluationController extends Controller
{
    use PaginationTrait;

    public function __construct
    (
        private EvaluationService $service,
        private TaskSubmissionRepository $taskSubmission,
        private TopicRepository $topic,
        private ClassroomStudentRepository $classroomStudent,
    ) {}

    public function update(EvaluationRequest $request): JsonResponse
    {
        try {
            $this->service->update($request);
            return ResponseHelper::success(message('alert.update_success'));
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }

    /**
     * export task submission from spesifik task
     *
     * @param Task $task
     * @return BinaryFileResponse|JsonResponse
     */
    public function export(Task $task): BinaryFileResponse|JsonResponse
    {
        try {
            if ($task->submission_type == SubmissionTypeEnum::LINK) {
                return Excel::download(new TaskAnswersExport($this->taskSubmission, $task), 'task-submissions-' . $task->id . '-' . now()->format('Y-m-d-H-i-s') . '.xlsx');
            } else {
                return $this->service->handleDownloadZipped($task);
            }
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.download_failed', $th));
        }
    }

    /**
     * export all task submission in many task by classroom_id and subject_id
     *
     * @param Classroom $classroom
     * @param Subject $subject
     * @return BinaryFileResponse|JsonResponse
     */
    public function exportMany(Request $request, Classroom $classroom, Subject $subject): BinaryFileResponse|JsonResponse
    {
        try {
            $topics = $this->topic->getByClassroomAndSubject($classroom->id, $subject->id, $request);
            $students = $this->classroomStudent->getByClassId($classroom->id);

            $tasks = $this->service->getDataForEvaluation($topics, $students);
            // return $tasks->topicTasks;
            return Excel::download(new TaskRecapReportExport($tasks), $classroom->name . '_' . $subject->name . '.xlsx');
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.download_failed', $th));
        }
    }

    /**
     * export all task submissions in all tasks in all topics by all teacher lesson schedules 
     *
     * @param Request $request
     * @return BinaryFileResponse|JsonResponse
     */
    public function exportAll(Request $request): BinaryFileResponse|JsonResponse
    {
        try {
            return $this->service->exportAll($request);
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.download_failed', $th));
        }
    }
}
