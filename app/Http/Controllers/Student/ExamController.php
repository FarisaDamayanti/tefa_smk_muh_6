<?php

namespace App\Http\Controllers\Student;

use App\Contracts\Repositories\ExamRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Helpers\SemesterHelper;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Services\Student\ExamService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function __construct
    (
        private ExamRepository $exam,
        private ExamService $service,
        private SchoolYearRepository $schoolYear,
        private SubjectRepository $subject,
    ){}

    /**
     * index exam
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $schoolYears = $this->schoolYear->getSort();
        $subjects = $this->subject->get();

        $request->merge(['per_page' => 4]);
        $request->merge(['semester' => $request->query('semester', SemesterHelper::getCurrentSemester())]);
        $request->merge(['school_year_id' => $request->query('school_year_id', $schoolYears->where('active', 1)->first()?->id)]);

        $rawData = $this->exam->search($request);
        $exams = $this->service->mappingIndexExam($rawData);

        return view('student.pages.exam.index', compact('exams', 'schoolYears', 'subjects'));
    }

    /**
     * show exam
     *
     * @param Exam $exam
     * @return View
     */
    public function show(Exam $exam): View
    {
        $this->authorize('canParticipate', $exam);
        return view('student.pages.exam.detail.index', compact('exam'));
    }
}
