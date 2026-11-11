<?php

namespace App\Http\Controllers\School;

use App\Contracts\Repositories\StudentExamRepository;
use App\Http\Controllers\Controller;

class StudentExamController extends Controller
{
    public function __construct(
        private StudentExamRepository $studentExam,
    ) {}

    /**
     * Show detail student exam
     */
    public function show(string $studentExam)
    {

        $studentExam = $this->studentExam->showResult($studentExam);

        return view('school.pages.exam.detail.exam-detail-show', compact('studentExam'));
    }
}
