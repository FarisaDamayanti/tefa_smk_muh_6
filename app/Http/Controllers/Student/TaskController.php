<?php

namespace App\Http\Controllers\Student;

use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Contracts\Repositories\TaskRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\Student\TaskDetailResource;
use App\Models\Task;
use App\Services\Student\TaskService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        private TaskRepository $task,
        private SubjectRepository $subject,
        private SchoolYearRepository $schoolYear,
        private TaskService $service,
    ) {}

    /**
     * get all data task student
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        if ($request->query('academic_year', null) !== null) {
            [$school_year_id, $semester] = explode('_', $request->query('academic_year'), 2);
            $semester = $semester == 'odd' ? 'ganjil' : 'genap';
            $request->merge(compact('school_year_id', 'semester'));
        }

        $student = auth_student();
        $subjects = $this->subject->getByStudent($student->id);
        $schoolYears = $this->schoolYear->get();

        $data = $this->task->getAllTaskStudent($student->id, $request);
        $tasks = $this->service->index($data);

        return view('student.pages.task.index', compact('tasks', 'subjects', 'schoolYears'));
    }

    /**
     * show detail task
     *
     * @param Task $task
     * @return View
     */
    public function show(Task $task): View
    {
        $studentId = auth_student()->id;

        $task->load([
            'topic.subject',
            'topic.classroom.levelClass',
            'topic.employee.user',
            'currentUserTaskSubmission' => function($query) use ($studentId) {
                $query->where('student_id', $studentId);
            },
        ]);

        return view('student.pages.task-detail.index', [
            'task' => $task,
            'topic' => $task->topic,
            'subject' => $task->topic->subject,
            'classroom' => $task->topic->classroom
        ]);
    }
}
