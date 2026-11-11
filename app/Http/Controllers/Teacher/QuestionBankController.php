<?php

namespace App\Http\Controllers\Teacher;

use App\Contracts\Repositories\ExamQuestionRepository;
use App\Contracts\Repositories\LevelClassRepository;
use App\Contracts\Repositories\QuestionBankRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Http\Controllers\Controller;
use App\Models\ExamQuestion;
use App\Models\QuestionBank;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionBankController extends Controller
{
    public function __construct(
        private QuestionBankRepository $questionBank,
        private ExamQuestionRepository $examQuestion,
        private SubjectRepository $subject,
        private LevelClassRepository $levelClass,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $employee_id = auth_employee()->id;
        $request->merge(['employee_id' => $employee_id]);

        $questionBanks = $this->questionBank->search($request); // paginated
        $filterSubjects = $this->subject->whereTeacher($employee_id);
        $levelClasses = $this->levelClass->get();

        return view('teacher.pages.question-bank.index', compact(
            'questionBanks',
            'filterSubjects',
            'levelClasses'
        ));
    }

    /**
     * create question bank
     *
     * @param QuestionBank $questionBank
     * @return View
     */
    public function create(QuestionBank $questionBank): View
    {
        $questionBank = $this->questionBank->loadQuestionCountByEmployee($questionBank);
        return view('teacher.pages.question-bank.create', compact('questionBank'));
    }

    /**
     * Show detail of bank with question listing
     *
     * @return View
     */
    public function show(Request $request, string $questionBank): View
    {
        $employeeId = auth_employee()->id;

        $request->merge(['employee_id' => $employeeId]);
        $questionBank = $this->questionBank->showDetailSearch($request, $questionBank);

        $request->merge(['question_bank_id' => $questionBank->id]);
        $questions = $this->examQuestion->search($request);

        $questionBanks = $this->questionBank->get();

        return view('teacher.pages.question-bank.detail', compact(
            'questionBank',
            'questions',
            'questionBanks'
        ));
    }

    /**
     * edit question bank
     *
     * @param QuestionBank $questionBank
     * @return View
     */
    public function edit(ExamQuestion $examQuestion): View
    {
        $examQuestion->loadMissing(['leftExamQuestionMatchings.leftItemAnswer', 'rightExamQuestionMatchings', 'sortExamQuestionOptions']);
        $questionBank = $this->questionBank->show($examQuestion->question_bank_id);
        $questionBank = $this->questionBank->loadQuestionCountByEmployee($questionBank);

        return view('teacher.pages.question-bank.edit', compact('examQuestion', 'questionBank'));
    }
}
