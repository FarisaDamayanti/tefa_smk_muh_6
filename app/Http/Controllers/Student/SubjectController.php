<?php

namespace App\Http\Controllers\Student;

use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Http\Controllers\Controller;
use App\Services\Student\SubjectService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function __construct
    (
        private SubjectRepository $subject,
        private ClassroomStudentRepository $classroomStudent,
        private SchoolYearRepository $schoolYear,
        private SubjectService $service,
    ){}
    
    /**
     * get all data subject by student logged in
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $classroomStudent = $this->classroomStudent->getByStudent(auth()->user()->student->id, $request);
        $schoolYears = $this->schoolYear->getSort();
        $subjects = [];

        if ($classroomStudent) {
            $rawData = $this->subject->getByClassroom($classroomStudent->classroom_id, $request);
            $subjects = $this->service->mapPagination($rawData, $classroomStudent->classroom_id);
        }

        return view('student.pages.subjects.index', compact('subjects', 'schoolYears'));
    }
}
