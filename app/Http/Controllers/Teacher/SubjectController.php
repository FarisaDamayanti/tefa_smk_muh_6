<?php

namespace App\Http\Controllers\Teacher;

use App\Contracts\Repositories\LessonScheduleRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Http\Controllers\Controller;
use App\Services\Teacher\SubjectService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class SubjectController extends Controller
{

    public function __construct(
        protected SubjectRepository $subjectRepository,
        protected SchoolYearRepository $schoolYearRepository,
        protected LessonScheduleRepository $lessonSchedule,

        protected SubjectService $subjectService
    ) {}

    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $lessonSchedules = $this->lessonSchedule->getByEmployee(auth_employee()->id, $request);
        $subjects = $this->subjectRepository->whereTeacherSearch(auth_employee()->id, $request);
        $subjects = $this->subjectService->mapPagination($subjects, $lessonSchedules);
        $schoolYears = $this->schoolYearRepository->getSort();
        return view('teacher.pages.e-learning.index', compact('subjects', 'schoolYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
