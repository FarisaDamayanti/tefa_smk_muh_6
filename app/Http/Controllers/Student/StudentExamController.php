<?php

namespace App\Http\Controllers\Student;

use App\Contracts\Repositories\StudentExamRepository;
use App\Http\Controllers\Controller;
use App\Models\StudentExam;
use Illuminate\Http\Request;

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
     * @return void
     */
    public function show(string $studentExam)
    {
        $studentExam = $this->studentExam->showResult($studentExam);

        return view('student.pages.detail-answer.index', compact('studentExam'));
    }
}
