<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\LessonScheduleRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Teacher\SubjectResource;
use App\Services\Teacher\SubjectService;
use App\Traits\PaginationTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class SubjectController extends Controller
{
    use PaginationTrait;

    public function __construct(
        private LessonScheduleRepository $lessonSchedule,
        private SubjectRepository $subjectRepository,
        private SubjectService $subjectService
    ) {}

    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = [
            'data' => [],
            'paginate' => $this->customPaginate(1, 1, $request->query('per_page', 10))
        ];

        try {
            $lessonSchedules = $this->lessonSchedule->getByEmployee(auth_employee()->id, $request);
            $subjects = $this->subjectRepository->whereTeacherSearch(auth_employee()->id, $request);

            if ($subjects->isNotEmpty()) {
                $mapped = $this->subjectService->mapPagination($subjects, $lessonSchedules);
                $data['data'] = SubjectResource::collection($mapped);
                $data['paginate'] = $this->customPaginate(
                    $mapped->currentPage(),
                    $mapped->lastPage(),
                    $request->query('per_page', 10)
                );
            }

            return ResponseHelper::success($data, message('alert.fetch_success'));
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }
}
