<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Contracts\Repositories\ExamClassroomRepository;
use App\Contracts\Repositories\LevelClassRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\Exam\ExamRecapResource;
use App\Http\Resources\Teacher\Exam\ExamRecapShowResource;
use App\Models\Exam;
use App\Services\Teacher\ExamService;
use App\Traits\PaginationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamRecapController extends Controller
{
    use PaginationTrait;

    public function __construct(
        private SubjectRepository $subject,
        private SchoolYearRepository $schoolYear,
        private LevelClassRepository $levelClass,
        private ClassroomStudentRepository $classroomStudent,
        private ExamService $service,
        private ExamClassroomRepository $examRepo
    ) {}

    /**
     * get all data exam classroom by teacher subject logged in
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $employee_id = auth_employee()->id;
            $request->merge(compact('employee_id'));
    
            $exams = $this->examRepo->searchExamResult($request);
            $data = ExamRecapResource::collection($exams);
    
            return ResponseHelper::success($data, message('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th->getMessage()));
        }
    }

    /**
     * show detail exam in role teacher
     *
     * @param Request $request
     * @param Exam $exam
     * @return JsonResponse
     */
    public function show(Request $request, Exam $exam): JsonResponse
    {
        try {
            $exam->loadMissing([
                'questionBank.subject',
                'questionBank.levelClass',
                'examClassrooms.classroom.levelClass',
                'examClassrooms.classroom.employee.user',
                'examClassrooms.classroom.classroomStudents',
                'studentExams.student.classroomStudents' => function ($query) use ($exam) {
                    $query->whereIn(
                        'classroom_id',
                        $exam->examClassrooms->pluck('classroom_id')
                    );
                }
            ]);

            $classroomIds = $exam->examClassrooms()->pluck('classroom_id')->toArray();
            $classroomStudents = $this->classroomStudent->whereInAndSearch($classroomIds, $request);
            $data = $this->service->mappingShow($exam, $classroomStudents);            
    
            return ResponseHelper::success($data, trans('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th->getMessage()));
        }
    }

    /**
     * show exam classroom detail
     *
     * @param Request $request
     * @param Exam $exam
     * @param string $classroom
     * @return JsonResponse
     */
    public function examDetail(Request $request, Exam $exam, string $classroom): JsonResponse
    {
        try {
            $exam->loadMissing([
                'questionBank.subject',
                'questionBank.levelClass',
                'examClassrooms' => function($query) use ($classroom) {
                    $query->where('classroom_id', $classroom);
                },
                'examClassrooms.classroom' => function ($query) {
                    $query->withCount('classroomStudents');
                },
                'examClassrooms.classroom.levelClass',
                'examClassrooms.classroom.employee.user',
                'studentExams' => function($query) use ($classroom) {
                    $query->whereRelation('student.classroomStudents', 'classroom_id', $classroom);
                },
                'studentExams.student.user',
            ]);
    
            $classroomStudents = $this->classroomStudent->whereInAndSearch([$classroom], $request);
            $rawData = $this->service->examClassroomDetail($exam, $classroomStudents, $request);
            $data = $this->service->mappingExamDetail($exam, $rawData);

            return ResponseHelper::success($data, trans('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th->getMessage()));
        }
    }
}
