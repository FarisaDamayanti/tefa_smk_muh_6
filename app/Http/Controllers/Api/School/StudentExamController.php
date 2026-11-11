<?php

namespace App\Http\Controllers\Api\School;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\ResetManyRequest;
use App\Models\StudentExam;
use App\Services\School\StudentExamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentExamController extends Controller
{
    public function __construct
    (
        private StudentExamService $service,
    ){}

    /**
     * reset student exam
     *
     * @param StudentExam $studentExam
     * @return JsonResponse
     */
    public function reset(StudentExam $studentExam): JsonResponse
    {
        try {
            $this->service->reset($studentExam);

            return ResponseHelper::success($studentExam, message('alert.reset_exam_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.reset_exam_failed'));
        }
    }

    /**
     * reset many student exam
     *
     * @param ResetManyRequest $request
     * @return JsonResponse
     */
    public function resetMany(ResetManyRequest $request): JsonResponse
    {
        try {
            $this->service->resetMany($request);

            return ResponseHelper::success($request->validated(), message('alert.reset_exam_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.reset_exam_failed', $th));
        }
    }
}
