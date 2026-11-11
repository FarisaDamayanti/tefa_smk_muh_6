<?php

namespace App\Http\Controllers\Teacher;

use App\Contracts\Repositories\ClassroomRepository;
use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Contracts\Repositories\LessonScheduleRepository;
use App\Contracts\Repositories\LevelClassRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Contracts\Repositories\TopicRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\SubjectResource;
use App\Models\Task;
use App\Models\Topic;
use App\Services\Teacher\EvaluationService;
use App\Services\Teacher\SubjectService;
use App\Services\Teacher\TaskService;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TaskController extends Controller
{
    public function __construct(
        private LessonScheduleRepository $lessonSchedule,
        private SubjectRepository $subject,
        private SubjectService $subjectService,
        private TopicRepository $topic,
        private ClassroomStudentRepository $classroomStudent,
        private SchoolYearRepository $schoolYear,
        private ClassroomRepository $classroom,

        private LevelClassRepository $levelClass,
        private EvaluationService $evalService,
        private TaskService $service,
    ) {}

    public function index(Request $request)
    {


        $employee = auth_employee();
        $lessonSchedules = $this->lessonSchedule->getByEmployee($employee->id, $request);
        $subjects = $this->subject->whereTeacherSearch($employee->id, $request);

        if ($subjects->isNotEmpty()) {
            $subjects = SubjectResource::collection(
                $this->subjectService->mapPagination($subjects, $lessonSchedules)
            )->toArray($request);
        }

        $filterSubjects = $this->subject->whereTeacher($employee->id);
        $filterSchoolYears = $this->schoolYear->getSort();
        $filterClassrooms = $this->classroom->whereEmployeeId($employee->id);

        return view('teacher.pages.value-recap.index', compact(
            'subjects',
            'filterSchoolYears',
            'filterSubjects',
            'filterClassrooms'
        ));
    }

    public function showByClassroom(Request $request, string $classroomId, string $subjectId)
    {
        $topics = $this->topic->getByClassroomAndSubject($classroomId, $subjectId, $request);
        $students = $this->classroomStudent->getByClassIdWithSearch($classroomId, $request);

        $tasks = $this->evalService->getDataForEvaluation($topics, $students);
        $schoolYears = $this->schoolYear->getSort();

        $topic = $topics->first() ?? Topic::with(['classroom.levelClass', 'subject'])
            ->where('classroom_id', $classroomId)
            ->where('subject_id', $subjectId)
            ->first();

        return view('teacher.pages.detail-recap.index', compact(
            'topic',
            'tasks',
            'schoolYears',
        ));
    }

    /**
     * show create task form
     *
     * @param Topic $topic
     * @return View|Factory
     */
    public function create(Topic $topic): View|Factory
    {
        return view('teacher.pages.task.create', compact('topic'));
    }

    /**
     * show edit task form
     *
     * @param Task $task
     * @return View|Factory
     */
    public function edit(Task $task): View|Factory
    {
        return view('teacher.pages.task.edit', compact('task'));
    }

    /**
     * show detail task
     *
     * @param Task $task
     * @return View|Factory
     */
    public function show(Request $request, Task $task): View|Factory
    {
        $classroomStudents = $this->classroomStudent->getByClassIdWithSearch($task->topic->classroom_id, $request);
        $classroomStudents->loadMissing(['student.taskSubmissions', 'classroom.levelClass']);
        $task->classroomStudents = $this->service->mappingData($task, $classroomStudents, $request);

        return view('teacher.pages.task.detail', compact('task'));
    }

    /**
     * download format import task question
     *
     * @return BinaryFileResponse|RedirectResponse
     */
    public function downloadFormat(): BinaryFileResponse|RedirectResponse
    {
        try{
            return response()->download(base_path('/public/file/excel-format-import-task-question.xlsx'), 'Format-Import-Soal.xlsx');
        } catch(\Throwable $th){
            return redirect()->back()->with('error', 'Terjadi masalah. ' . $th->getMessage());
        }
    }
}
