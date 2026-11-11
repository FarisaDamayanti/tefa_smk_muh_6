<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\TopicInterface;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TopicRepository extends BaseRepository implements TopicInterface
{
    public function __construct(Topic $topic)
    {
        $this->model = $topic;
    }

    public function get(): mixed
    {
        return $this->model->query()->get();
    }

    public function store(array $data): mixed
    {
        return $this->model->query()->create($data);
    }

    public function show(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id);
    }

    public function update(mixed $id, array $data): mixed
    {
        return $this->model->query()->findOrFail($id)->update($data);
    }

    public function delete(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id)->delete();
    }

    public function where(mixed $data): mixed
    {
        return $this->model->query()->where('employee_id', $data)->get();
    }

    /**
     * Get by student and subject id
     *
     * @param Request $request
     * @param string $studentId
     * @param string $subjectId
     * @param string $classroomId
     * @return LengthAwarePaginator
     */
    public function getBySubject(Request $request, string $studentId, string $subjectId, string $classroomId): LengthAwarePaginator
    {
        return $this->model->query()
            ->when($request->semester, function($q) use ($request) {
                $q->where('semester', $request->semester);
            })
            ->when($request->search, function($q) use ($request) {
                $q->where('title', 'like', "%$request->search%");
            })
            ->where('classroom_id', $classroomId)
            ->whereRelation('classroom.classroomStudents', 'student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->paginate($request->query('per_page', 10));
    }

    /**
     * get data by employee_id, subject_id, classroom_id, with paginate and filter
     *
     * @param string $employee_id
     * @param string $subject_id
     * @param string $classroom_id
     * @param Request $request
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function getByEmployeeSubjectClassroom(string $employee_id, string $subject_id, string $classroom_id, Request $request, int $paginate = 10): LengthAwarePaginator
    {
        return $this->model->query()
            ->with(['modules', 'tasks'])
            ->where('employee_id', $employee_id)
            ->where('subject_id', $subject_id)
            ->where('classroom_id', $classroom_id)
            ->when($request->search, function($query) use ($request) {
                $query->where('title', 'like', "%$request->search%");
            })
            ->when($request->semester, function($query) use ($request) {
                $query->where('semester', $request->semester);
            })
            ->orderby('topic_number')
            ->paginate($paginate);
    }

    /**
     * get by classroom and subject with relation and sort by topic number
     *
     * @param string $classroomId
     * @param string $subjectId
     * @return Collection
     */
    public function getByClassroomAndSubject(string $classroomId, string $subjectId, Request $request): Collection
    {
        return $this->model->query()
            ->where('subject_id', $subjectId)
            ->where('classroom_id', $classroomId)
            ->when($request->semester, function($query, $semester) {
                $query->where('semester', $semester);
            })
            ->with(['classroom.schoolYear', 'subject', 'tasks'])
            ->orderBy('topic_number')
            ->get();
    }

    /**
     * get count by
     *
     * @param string $subject_id
     * @param string $employee_id
     * @param string $classroom_id
     * @param string $semester
     * @return int
     */
    public function getCountWithSpesificParam(array $criteria): int
    {
        return $this->model->query()
            ->where($criteria)
            ->count();
    }

    /**
     * reorder topic number (use in delete topic)
     *
     * @param array $criteria
     * @return void
     */
    public function reorderTopicNumber(array $criteria): void
    {
        $topics = $this->model->query()
            ->where($criteria)
            ->orderBy('topic_number')
            ->get(['id']);

        DB::transaction(function () use ($topics) {
            $topics->each(function ($topic, $index) {
                $topic->update(['topic_number' => $index + 1]);
            });
        });
    }
}
