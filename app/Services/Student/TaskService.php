<?php

namespace App\Services\Student;

use App\Enums\StatusSubmissionTaskEnum;
use App\Enums\SubmissionStatusEnum;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    /**
     * mapping data for index student task
     *
     * @param LengthAwarePaginator $data
     * @return LengthAwarePaginator
     */
    public function index(LengthAwarePaginator $data): LengthAwarePaginator
    {
        foreach($data as $task) {
            $taskSubmission = $task->currentUserTaskSubmission;

            if ($taskSubmission) {
                $status = SubmissionStatusEnum::compute($taskSubmission->status, $task->allow_overdue, $taskSubmission->grade);
            } else {
                $status = SubmissionStatusEnum::NOT_YET_SUBMITTED;
            }

            $task->subject_name = $task->topic->subject->name;
            $task->school_year = $task->topic->classroom->schoolYear->school_year;
            $task->semester = $task->topic->semester;
            $task->status = [
                'label' => $status->label(),
                'color' => $status->color(),
            ];
        }

        return $data;
    }

    /**
     * Create statistic from given tasks collection
     *
     * @param Collection<int, Task> $tasks Tasks from repository
     * @return array
     */
    public function createStatistic(Collection $tasks): array
    {
        $totalTask = 0;
        $taskSubmitted  = 0;
        $taskNotSubmitted  = 0;
        $taskNotEvaluated  = 0;

        $totalTask = $tasks->reduce(function($carry, $item, $key) use ($taskSubmitted, $taskNotEvaluated, $taskNotSubmitted){
            $carry++;
            $hasSubmit = $item->taskSubmissions->isNotEmpty();

            if($hasSubmit && $task = $item->taskSubmissions->first()){
                if($task->grade !== null){
                    $taskSubmitted++;
                } else {
                    $taskNotEvaluated++;
                }
            } elseif (! $hasSubmit){
                $taskNotSubmitted++;
            }
        }, $totalTask);

        return [
            'total_tasks' => $totalTask,
            'submitted_tasks' => $taskSubmitted,
            'not_evaluated_tasks' => $taskNotEvaluated,
            'not_submitted_tasks' => $taskNotSubmitted
        ];
    }
}
