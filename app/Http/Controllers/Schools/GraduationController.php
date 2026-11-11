<?php

namespace App\Http\Controllers\Schools;

use App\Contracts\Repositories\ClassroomRepository;
use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Contracts\Repositories\GraduationRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\GraduationRequest;
use App\Services\School\GraduationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GraduationController extends Controller
{
    public function __construct
    (
        private GraduationRepository $graduation,
        private ClassroomRepository $classroom,
        private ClassroomStudentRepository $classroomStudent,
        private SchoolYearRepository $schoolYear,
        private GraduationService $service,
    ){}

    /**
     * logic get data student in 
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $schoolYears = $this->schoolYear->get();
        $activeSchoolYear = $schoolYears->where('active', 1)->first();
        $request->merge(['school_year_id' => $request->query('school_year_id', $activeSchoolYear->id)]);

        $graduation = $this->graduation->getBySchoolYear($request->school_year_id);
        $classrooms = $this->classroom->getByClassLevelAndSchoolYear(12, $request->school_year_id);
        $graduationDetails = $this->classroomStudent->getStudentGraduates(empty($request->classroom_id) ? $classrooms->pluck('id')->toArray() : [$request->classroom_id], $request);

        if (($request->school_year_id != $activeSchoolYear->id) || ($request->school_year_id == $activeSchoolYear->id && $graduation?->announcement_date?->isPast() && empty($graduationDetails['original_graduation_data']))) {
            $canUpdate = false;
        } else {
            $canUpdate = true;
        }

        return view('school.pages.graduation.index', compact('schoolYears', 'graduation', 'classrooms', 'graduationDetails', 'canUpdate'));
    }

    /**
     * store or update data graduation
     * 
     * @param GraduationRequest $request
     * @return RedirectResponse
     */
    public function storeOrUpdate(GraduationRequest $request): RedirectResponse
    {
        try {
            $this->service->storeOrUpdate($request);

            return redirect()->back()->with('success', 'Berhasil menyimpan data kelulusan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menyimpan data kelulusan. ' . $th->getMessage());
        }
    }
}
