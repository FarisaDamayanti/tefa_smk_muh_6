<?php

namespace App\Http\Controllers\School;

use App\Contracts\Repositories\EmployeeRepository;
use App\Contracts\Repositories\ExamQuestionRepository;
use App\Contracts\Repositories\LevelClassRepository;
use App\Contracts\Repositories\QuestionBankRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class QuestionBankController extends Controller
{
    public function __construct(
        private QuestionBankRepository $questionBank,
        private SubjectRepository $subjectRepository,
        private SchoolYearRepository $schoolYearRepository,
        private LevelClassRepository $levelClassRepository,
        private ExamQuestionRepository $examQuestion,
        private EmployeeRepository $employee
    ) {}

    public function index(Request $request): Factory|View
    {
        $questionBanks = $this->questionBank->search($request);

        $subjects      = $this->subjectRepository->get();
        $levelClasses  = $this->levelClassRepository->get();

        return view(
            'school.pages.question-bank.index',
            compact(
                'questionBanks',
                'subjects',
                'levelClasses'
            )
        );
    }



    public function create(): Factory|View
    {
        $filterSubjects    = $this->subjectRepository->get();
        $filterSchoolYears = $this->schoolYearRepository->get();
        $levelClasses      = $this->levelClassRepository->get();

        return view('school.pages.question-bank.create', compact(
            'filterSubjects',
            'filterSchoolYears',
            'levelClasses'
        ));
    }


    public function edit(string $id): Factory|View
    {
        $questionBank      = $this->questionBank->show($id);
        $filterSubjects    = $this->subjectRepository->get();
        $filterSchoolYears = $this->schoolYearRepository->get();
        $levelClasses      = $this->levelClassRepository->get();

        return view('school.pages.question-bank.edit', compact(
            'questionBank',
            'filterSubjects',
            'filterSchoolYears',
            'levelClasses'
        ));
    }


    public function show(Request $request, string $id): Factory|View
    {
        $questionBank = $this->questionBank->showDetailSearch($request, $id);
        $request->merge(['question_bank_id' => $id]);
        $questions = $this->examQuestion->search($request);
        $filterEmployees = $this->employee->getHasSubmittedQuestions($id);

        return view('school.pages.question-bank.detail.index', compact(
            'questionBank',
            'questions',
            'filterEmployees'
            ));
    }

    public function review(Request $request, string $id): Factory|View
    {
        $questionBank = $this->questionBank->showDetailSearch($request, $id);
        $request->merge(['question_bank_id' => $id]);
        $questions = $this->examQuestion->searchGet($request);

        return view('school.pages.question-bank.detail.review', compact(
            'questionBank',
            'questions'
        ));
    }
}
