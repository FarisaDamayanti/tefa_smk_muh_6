<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Parent is topic, so we map by it
 */
class TopicTaskEvaluationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tasks = [];

        $groupedByStudent = $this->tasks
        ->flatMap(function ($task) use (&$tasks) {
            return $task->latestTaskSubmission->map(function($item) use ($task, &$tasks) {
                $task_no = $tasks[$task->id] ?? ($tasks[$task->id] = count($tasks) + 1);
                $task->task_no = $task_no;
                $item->task = $task->only(['id', 'title', 'description', 'task_no']);
                return $item;
            });
        })
        ->groupBy(['student_id']);
        // ->map(function ($submissions, $studentId) {
        //     $firstSubmission = $submissions->first();

        //     return [
        //         'student_id' => $studentId,
        //         'student' => $firstSubmission->student ?? null,
        //         'tasks' => $submissions->map(function ($submission) {
        //             return [
        //                 'submission' => $submission,
        //                 'task' => $submission->task
        //             ];
        //         })
        //     ];
        // })
        // ->values();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'semester' => $this->semester,
            'student_tasks' =>  $groupedByStudent
    ];
    }
}
