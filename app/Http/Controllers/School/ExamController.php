<?php

namespace App\Http\Controllers\School;

use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Contracts\Repositories\ExamRepository;
use App\Contracts\Repositories\LevelClassRepository;
use App\Contracts\Repositories\QuestionBankRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Helpers\SemesterHelper;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Services\School\ExamService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function __construct(
        private ExamRepository $exam,
        private QuestionBankRepository $questionBank,
        private SchoolYearRepository $schoolYear,
        private SubjectRepository $subject,
        private LevelClassRepository $levelClass,
        private ClassroomStudentRepository $classroomStudent,
        private ExamService $service,
    ) {}

    /**
     * index exam data
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $schoolYears = $this->schoolYear->getSort();
        $subjects = $this->subject->get();
        $levelClasses = $this->levelClass->get();

        $request->merge(['school_year_id' => $request->query('school_year_id', $schoolYears->where('active', 1)->first()?->id)]);
        $currentSemester = SemesterHelper::getCurrentSemester();
        $request->merge(['semester' =>  $request->query('semester',  $currentSemester)]);
        $request->merge(['per_page' => 6]);

        $exams = $this->exam->search($request);

        return view('school.pages.exam.index', compact('exams', 'schoolYears', 'subjects', 'levelClasses'));
    }

    /**
     * exam show page
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

        return view('school.pages.exam.detail.index', compact('exam'));
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
            'examClassrooms' => function ($query) use ($classroom) {
                $query->where('classroom_id', $classroom);
            },
            'examClassrooms.classroom' => function ($query) {
                $query->withCount('classroomStudents');
            },
            'examClassrooms.classroom.levelClass',
            'examClassrooms.classroom.employee.user',
            'studentExams' => function ($query) use ($classroom) {
                $query->whereRelation('student.classroomStudents', 'classroom_id', $classroom);
            },
            'studentExams.student.user',
        ]);

        $classroomStudents = $this->classroomStudent->whereInAndSearch([$classroom], $request);
        $data = $this->service->examClassroomDetail($exam, $classroomStudents, $request);

        $exam = $data['exam'];
        $studentExams = $data['studentExams'];
        $classroom = $exam->examClassrooms->first()->classroom;

        return view('school.pages.exam.detail.exam-detail', compact('exam', 'studentExams', 'classroom'));
    }

    /**
     * exam create page
     *
     * @return View
     */
    public function create(): View
    {
        $schoolYears = $this->schoolYear->getSort();
        $questionBanks = $this->questionBank->get();

        $currentSemester = SemesterHelper::getCurrentSemester();

        return view('school.pages.exam.create', compact('schoolYears', 'questionBanks', 'currentSemester'));
    }

    /**
     * exam edit page
     *
     * @param Exam $exam
     * @return View
     */
    public function edit(Exam $exam): View
    {
        $schoolYears = $this->schoolYear->getSort();
        $questionBanks = $this->questionBank->get();
        $exam->loadMissing('studentExams');

        return view('school.pages.exam.edit', compact('exam', 'schoolYears', 'questionBanks'));
    }
}
