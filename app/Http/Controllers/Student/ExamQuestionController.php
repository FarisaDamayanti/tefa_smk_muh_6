<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Services\Student\ExamQuestionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamQuestionController extends Controller
{
    public function __construct
    (
        private ExamQuestionService $service,
    ){}

    /**
     * show exam question by number
     *
     * @param Exam $exam
     * @param integer $number
     * @return View
     */
    public function show(Exam $exam): View
    {
        $this->authorize('canAccess', $exam);
        $data = $this->service->show($exam);

        return view('student.pages.exam.working.index', compact('data', 'exam'));
    }
}
