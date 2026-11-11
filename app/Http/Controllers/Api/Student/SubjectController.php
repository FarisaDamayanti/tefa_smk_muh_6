<?php

namespace App\Http\Controllers\Api\Student;

use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Student\SubjectResource;
use App\Models\User;
use App\Services\Student\SubjectService;
use App\Traits\PaginationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use PaginationTrait;

    public function __construct
    (
        private SubjectRepository $subject,
        private ClassroomStudentRepository $classroomStudent,
        private SubjectService $service,
    ){}

    /**
     * get subject data by user with paginate and filter
     * 
     * @param User $user
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $classroomStudent = $this->classroomStudent->getByStudent(auth_student()->id, $request);
            $data = [];

            if ($classroomStudent) {
                $rawData = $this->subject->getByClassroom($classroomStudent->classroom_id, $request);
                $mapped = $this->service->mapPagination($rawData, $classroomStudent->classroom_id);
                $data['data'] = SubjectResource::collection($mapped);
                $data['paginate'] = $this->customPaginate($rawData->currentPage(), $rawData->lastPage(), $rawData->perPage());
            }

            return ResponseHelper::success($data, trans('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(trans('alert.fetch_failed') . '. ' . $th->getMessage());
        }
    }
}
