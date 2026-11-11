<?php

namespace App\Services\Teacher;

use App\Contracts\Repositories\TaskQuestionOptionRepository;
use App\Contracts\Repositories\TaskQuestionRepository;
use App\Contracts\Repositories\TaskRepository;
use App\Enums\StatusSubmissionTaskEnum;
use App\Enums\SubmissionStatusEnum;
use App\Enums\SubmissionTypeEnum;
use App\Enums\TaskQuestionTypeEnum;
use App\Http\Requests\Teacher\StoreTaskRequest;
use App\Models\Task;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaskService
{
    public function __construct
    (
        private TaskRepository $task,
        private TaskQuestionRepository $taskQuestion,
        private TaskQuestionOptionRepository $taskQuestionOption,
    ){}

    /**
     * logic for create data
     *
     * @param StoreTaskRequest $request
     * @return array
     */
    public function store(StoreTaskRequest $request, Topic $topic): Task
    {
        DB::beginTransaction();
        
        try {
            $task = $this->task->store([
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'submission_type' => $request->submission_type,
                'allow_overdue' => $request->allow_overdue,
                'is_multiple_choice' => $request->is_multiple_choice,
                'can_view_answer_result' => $request->can_view_answer_result ?? null,
                'topic_id' => $topic->id,
            ]);

            if ($request->is_multiple_choice) {
                $this->storeTaskQuestions($task, $request->task_questions);
            }

            DB::commit();
            return $task;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * logic bulk insert task question and task question option
     * 
     * @param Task $task
     * @param array $taskQuestions
     * @return void
     */
    private function storeTaskQuestions(Task $task, array $taskQuestions): void
    {
        $now = now();
        $questionsData = [];
        $optionsData = [];

        foreach ($taskQuestions as $questionInput) {
            $questionId = Str::uuid();
            $questionType = $this->determineQuestionType($questionInput['options']);

            $questionsData[] = [
                'id' => $questionId,
                'question' => $questionInput['question'],
                'type' => $questionType,
                'task_id' => $task->id,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            foreach ($questionInput['options'] as $option) {
                $optionsData[] = [
                    'id' => Str::uuid(),
                    'task_question_id' => $questionId,
                    'option_label' => $option['option_label'],
                    'option_text' => $option['option_text'],
                    'is_correct' => $option['is_correct'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!empty($questionsData)) {
            $this->taskQuestion->insert($questionsData);
        }
        
        if (!empty($optionsData)) {
            $this->taskQuestionOption->insert($optionsData);
        }
    }

    /**
     * check type for task question
     * 
     * @param array $options
     * @return string
     */
    private function determineQuestionType(array $options): string
    {
        $correctAnswerCount = collect($options)
            ->filter(fn($option) => $option['is_correct'])
            ->count();

        return $correctAnswerCount > 1 
            ? TaskQuestionTypeEnum::MULTIPLE_CHOICE->value
            : TaskQuestionTypeEnum::SINGLE_CHOICE->value;
    }

    /**
     * mapping data for detail task with submission task student
     *
     * @param Task $task
     * @param $data
     * @return
     */
    public function mappingData(Task $task, $data, Request $request): Collection
    {
        $status = $request->query('status', null);

        return $data->map(function($classroomStudent) use ($task) {
            $student = $classroomStudent->student;

            $currentTaskSubmission = $student->taskSubmissions->where('task_id', $task->id)->first();

            $classroomStudent->user_name = $student->user->name;
            $classroomStudent->classroom_name = $classroomStudent->classroom->levelClass->name . ' ' . $classroomStudent->classroom->name;

            if($currentTaskSubmission) {
                $classroomStudent->submission_task_id = $currentTaskSubmission->id;
                $classroomStudent->grade = $currentTaskSubmission?->grade;
                $classroomStudent->note = $currentTaskSubmission?->note;
                $classroomStudent->answer = $currentTaskSubmission?->answer;
                $classroomStudent->file = $task->submission_type->value == SubmissionTypeEnum::FILE->value ? ($currentTaskSubmission ? url('storage/' . $currentTaskSubmission->answer) : null) : null;
                $classroomStudent->submit_date = $currentTaskSubmission->submit_date;
                $status = SubmissionStatusEnum::compute($currentTaskSubmission?->status, $task->allow_overdue, $currentTaskSubmission?->grade);
            } else {
                $status = SubmissionStatusEnum::NOT_YET_SUBMITTED;
            }

            $classroomStudent->status = [
                'label' => $status->label(),
                'color' => $status->color(),
                'status' => $status
            ];

            return $classroomStudent;
        })->filter(function($item) use ($status){
            if($status){
                return $item->status['status']->value == $status;
            }

            return true;
        })->values();
    }
}
