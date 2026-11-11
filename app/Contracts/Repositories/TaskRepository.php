<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\TaskInterface;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskRepository extends BaseRepository implements TaskInterface
{
    public function __construct(Task $task)
    {
        $this->model = $task;
    }

    /**
     * get all data
     *
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->model->query()->get();
    }

    /**
     * create new data
     *
     * @param array $data
     * @return mixed
     */
    public function store(array $data): mixed
    {
        return $this->model->query()->create($data);
    }

    /**
     * show detail data by id
     *
     * @param mixed $id
     * @return mixed
     */
    public function show(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id);
    }

    /**
     * update data by id
     *
     * @param mixed $id
     * @param array $data
     * @return mixed
     */
    public function update(mixed $id, array $data): mixed
    {
        return $this->model->query()->findOrFail($id)->update($data);
    }

    /**
     * delete data by id
     *
     * @param mixed $id
     * @return mixed
     */
    public function delete(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id)->delete();
    }

    /**
     * Get by student with their task submissions
     *
     * @param string $student
     * @return Collection
     */
    public function getWithRelation(string $student): Collection
    {
        return $this->model->query()
        ->whereRelation('topic.classroom.classroomStudents', 'student_id', $student)
        ->with(['taskSubmissions' => function($query) use ($student) {
            $query->where('student_id', $student);
        }])
        ->get();
    }

    /**
     * get latest task filtered by classroom id with limit
     *
     * @param string|null $classroom_id
     * @param int $limit
     * @return Collection
     */
    public function getLatestTask(?string $classroom_id, int $limit = 5): Collection
    {
        return $this->model->query()
            ->where('classroom_id', $classroom_id)
            ->limit($limit)
            ->latest()
            ->get();
    }

    public function getByTopicWithSubmission(string $topicId)
    {
        return $this->model->query()
            ->where('topic_id', $topicId)
            ->with(['latestTaskSubmission', 'topic.classroom', 'topic.subject'])
            ->oldest()
            ->get();
    }

    /**
     * query for get task by student and filtering
     *
     * @param string $student_id
     * @param Request $request
     * @param integer $paginate
     * @return LengthAwarePaginator
     */
    public function getAllTaskStudent(string $student_id, Request $request, int $paginate = 10): LengthAwarePaginator
    {
        return $this->model->query()
            ->with([
                'topic.classroom.schoolYear',
                'currentUserTaskSubmission',
                'topic.schoolYear',
            ])
            ->when($request->school_year_id, function($query) use ($request) {
                $query->whereRelation('topic.classroom', 'school_year_id', $request->school_year_id);
            }, function($query) {
                $query->whereRelation('topic.classroom.schoolYear', 'active', 1);
            })
            ->whereRelation('topic.classroom.classroomStudents', 'student_id', $student_id)
            ->when($request->search, function($query) use ($request) {
                $query->where('title', 'like', "%$request->search%");
            })
            ->when($request->semester, function($query) use ($request) {
                $query->whereRelation('topic', 'semester', $request->semester);
            })
            ->when($request->subject_id, function($query) use ($request) {
                $query->whereRelation('topic', 'subject_id', $request->subject_id);
            })
            ->when($request->deadline == 'terjauh', function($query) {
                $query->orderBy('deadline', 'asc');
            }, function($query) {
                $query->orderBy('deadline', 'desc');
            })
            ->paginate($paginate);
    }

    /**
     * Get student tasks with incoming deadline of 3 days
     *
     * @param string $student
     * @return Collection
     */
    public function getIncomingDeadlineTasksByStudent(string $student): Collection
    {
        return $this->model->query()
            ->whereRelation('topic.classroom.classroomStudents', 'student_id', $student)
            ->whereBetween('deadline', [now(), now()->copy()->addDays(3)])
            ->whereDoesntHave('taskSubmissions', function ($query) use ($student) {
                $query->where('student_id', $student);
            })
            ->get();
    }

    public function getNotDoneAndDoneTaskCount(string $classroom_id, string $student_id)
    {
        $query = $this->model->query()->whereRelation('topic', 'classroom_id', $classroom_id);

        $total = (clone $query)->count();

        $submitted = (clone $query)
            ->whereRelation('taskSubmissions', 'student_id', $student_id)
            ->count();

        return [
            'submitted' => $submitted,
            'not_submit' => $total - $submitted,
        ];
    }
}
