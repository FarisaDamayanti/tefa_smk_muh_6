<?php

namespace App\Http\Controllers\Teacher;

use App\Contracts\Repositories\LevelClassRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Enums\SemesterEnum;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\Teacher\ExamQuestionService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ExamQuestionController extends Controller
{
    public function __construct
    (
        private SubjectRepository $subject,
        private LevelClassRepository $levelClass,
        private SchoolYearRepository $schoolYear,
        private ExamQuestionService $service,
    ){}

    /**
     * get data for question bank index
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $subjects = $this->subject->get();
        $levelClasses = $this->levelClass->get();
        $schoolYear = $this->schoolYear->get();
        $semesters = SemesterEnum::cases();
        $data = $this->service->index($request);

        return view('', compact('data', 'subjects', 'levelClasses', 'schoolYear', 'semesters'));
    }

    public function downloadTemplate()
    {
        try{
            return response()->download(base_path('/public/file/excel-format-import-exam-question_v2.xlsx'), 'Format-Import-Soal.xlsx');
        } catch(Throwable $th){
            return redirect()->back()->with('error', 'Terjadi masalah. ' . $th->getMessage());
        }
    }
}
