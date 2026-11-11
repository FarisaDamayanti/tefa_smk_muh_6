<?php

namespace App\Http\Controllers\Teacher;

use App\Contracts\Repositories\StudentExamRepository;
use App\Http\Controllers\Controller;
use App\Models\StudentExam;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentExamController extends Controller
{
    public function __construct
    (
        private StudentExamRepository $studentExam,
    ){}
    
    /**
     * show detail student exam
     *
     * @param StudentExam $studentExam
     * @return View
     */
    public function show(string $studentExam): View
    {
        $studentExam = $this->studentExam->showResult($studentExam);

        return view('teacher.pages.exam-monitoring.student-detail.index', compact('studentExam'));
    }
}
