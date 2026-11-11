<?php

namespace App\Contracts\Repositories\Teachers;

use App\Contracts\Interfaces\Teachers\TeacherJournalInterface;
use App\Contracts\Repositories\BaseRepository;
use App\Models\TeacherJournal;
use Carbon\Carbon;
use FontLib\Table\Type\maxp;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TeacherJournalRepository extends BaseRepository implements TeacherJournalInterface
{
    public function __construct(TeacherJournal $city)
    {
        $this->model = $city;
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

    public function updateWithLesson(array $data, mixed $id): mixed
    {
        return $this->model->query()->where('lesson_schedule_id')->update($data);
    }

    public function delete(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id)->delete();
    }

    public function paginate(): mixed
    {
        return 0;
    }

    public function getLessonSchedule(mixed $id): mixed
    {
        return $this->model->query()->where('lesson_schedule_id', $id)->first();
    }

    public function histories(mixed $id,Request $request): mixed
    {
        return $this->model->query()
            ->whereRelation('lessonSchedule.teacherSubject.employee.user', 'id', $id)
            ->with('attendanceJournals')
            ->when($request->search, function ($query) use ($request) {
                $query->where('title', 'LIKE', '%' . $request->search . '%');
            })->when($request->filter === "terbaru", function($query) {
                $query->latest();
            })
            ->when($request->filter === "terlama", function($query) {
                $query->oldest();
            })->when($request->date, function ($query) use ($request) {
                $query->where('date', 'LIKE', '%' . $request->date . '%');
            })
            ->latest()
            ->get();
    }

    public function getJournalToday(mixed $id): mixed
    {
        return $this->model->query()
            ->whereRelation('lessonSchedule.teacherSubject.employee.user', 'id', $id)
            ->whereDate('created_at', today())
            ->get();
    }

    /**
     * query get all journal by user
     *
     * @param mixed $id
     * @param Request $request
     */
    public function getAllJournalByUser(mixed $id, Request $request)
    {
        return $this->model->query()
            ->whereRelation('lessonSchedule.teacherSubject.employee.user', 'id', $id)
            ->with(['lessonSchedule.teacherSubject.subject', 'lessonSchedule.classroom', 'attendanceJournals'])
            ->when($request->date, function($query) use ($request) {
                $query->whereDate('date', $request->date);
            })
            ->when($request->subject, function($query) use ($request) {
                $query->whereHas('lessonSchedule.teacherSubject', function($query) use ($request) {
                    $query->where('id', $request->subject);
                });
            })
            ->paginate($request->per_page ?? 10);
    }

    public function whereTeacher(mixed $employee_id, mixed $subject_id): mixed
    {
        return $this->model->query()
            ->whereRelation('lessonSchedule.teacherSubject', 'employee_id', $employee_id)
            ->whereRelation('lessonSchedule.teacherSubject', 'subject_id', $subject_id)
            ->first();
    }

    public function getByTeacher(mixed $employee_id): mixed
    {
        return $this->model->query()
            ->whereRelation('lessonSchedule.teacherSubject', 'employee_id', $employee_id)
            ->latest()
            ->get();
    }

    public function getByLessonSchedule(mixed $id): mixed
    {
        return $this->model->query()
            ->where('lesson_schedule_id', $id)
            ->whereDate('created_at', today())
            ->first();
    }

    /**
     * get teacher journal by classroom_id, subject_id, semester, and filter
     * 
     * @param string $classroomId
     * @param string $subjectId
     * @param string $semester
     * @param Request $request
     * @return Collection
     */
    public function getByClassroomAndSubject(string $classroomId, string $subjectId, string $semester, Request $request): Collection
    {
        return $this->model->query()
            ->whereRelation('lessonSchedule.classroom', 'id', $classroomId)
            ->whereRelation('lessonSchedule.teacherSubject', 'subject_id', $subjectId)
            ->where('semester', $semester)
            ->with([
                'lessonSchedule.classroom' => function($q) use ($subjectId, $classroomId) {
                    $q->with(['modules' => function ($q) use ($subjectId) {
                        $q->where('subject_id', $subjectId);
                    }])->where('id', $classroomId);
                }
            ])
            ->when($request->search, function($query) use ($request) {
                $query->where('title', 'like', "%$request->search%");
            })
            ->when($request->date, function($query) use ($request) {
                $query->whereDate('date', $request->date);
            })
            ->orderBy('meeting', 'asc')
            ->get();
    }

    public function getStudentAttendance(string $id): ?TeacherJournal
    {
        return $this->model->query()
        ->with([
            'attendanceJournals.classroomStudent.student',
            'attendanceJournals.classroomStudent.classroom'
        ])->findOrFail($id);
    }
}
