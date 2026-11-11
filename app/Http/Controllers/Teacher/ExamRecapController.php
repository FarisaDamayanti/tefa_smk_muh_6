<?php

namespace App\Http\Controllers\Teacher;

use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Contracts\Repositories\ExamClassroomRepository;
use App\Contracts\Repositories\LevelClassRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Services\Teacher\ExamService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamRecapController extends Controller
{
    public function __construct(
        private SubjectRepository $subject,
        private SchoolYearRepository $schoolYear,
        private LevelClassRepository $levelClass,
        private ClassroomStudentRepository $classroomStudent,
        private ExamService $service,
        private ExamClassroomRepository $examRepo
    ) {}

    public function index(Request $request)
    {
        $employee_id = auth_employee()->id;

        $request->merge(compact('employee_id'));

        $exams = $this->examRepo->searchExamResult($request);

        $filterSubjects = $this->subject->whereTeacher($employee_id);
        $filterSchoolYears = $this->schoolYear->getSort();
        $filterLevelClasses = $this->levelClass->getSort();

        return view(
            'teacher.pages.exam-monitoring.index',
            compact(
                'exams',
                'filterSubjects',
                'filterSchoolYears',
                'filterLevelClasses'
            )
        );
    }

    /**
     * show detail exam in role teacher
     *
     * @param Request $request
     * @param Exam $exam
     * @return View
     */
    public function show(Request $request, Exam $exam): View
    {
        $exam->loadMissing([
            'questionBank.subject',
            'questionBank.levelClass',
            'examClassrooms.classroom.levelClass',
            'examClassrooms.classroom.employee.user',
            'examClassrooms.classroom.classroomStudents',
            'studentExams.student.classroomStudents' => function ($query) use ($exam) {
                $query->whereIn(
                    'classroom_id',
                    $exam->examClassrooms->pluck('classroom_id')
                );
            }
        ]);

        $classroomIds = $exam->examClassrooms()->pluck('classroom_id')->toArray();
        $classroomStudents = $this->classroomStudent->whereInAndSearch($classroomIds, $request);
        $exam = $this->service->show($exam, $classroomStudents);

        return view(
            'teacher.pages.exam-monitoring.detail.detail',
            compact('exam')
        );
    }

    /**
     * show exam classroom detail
     *
     * @param Request $request
     * @param Exam $exam
     * @param string $classroom
     * @return View
     */
    public function examDetail(Request $request, Exam $exam, string $classroom): View
    {
        $exam->loadMissing([
            'questionBank.subject',
            'questionBank.levelClass',
            'examClassrooms' => function($query) use ($classroom) {
                $query->where('classroom_id', $classroom);
            },
            'examClassrooms.classroom' => function ($query) {
                $query->withCount('classroomStudents');
            },
            'examClassrooms.classroom.levelClass',
            'examClassrooms.classroom.employee.user',
            'studentExams' => function($query) use ($classroom) {
                $query->whereRelation('student.classroomStudents', 'classroom_id', $classroom);
            },
            'studentExams.student.user',
        ]);

        $classroomStudents = $this->classroomStudent->whereInAndSearch([$classroom], $request);
        $data = $this->service->examClassroomDetail($exam, $classroomStudents, $request);

        $exam = $data['exam'];
        $studentExams = $data['studentExams'];
        $classroom = $exam->examClassrooms->first()->classroom;

        return view('teacher.pages.exam-monitoring.detail.exam-detail',
            compact('exam', 'studentExams', 'classroom')
        );
    }
}
