<?php

namespace App\Http\Controllers\Api\School;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\StoreExamRequest;
use App\Http\Requests\School\UpdateExamRequest;
use App\Models\Exam;
use App\Services\School\ExamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExamController extends Controller
{
    public function __construct
    (
        private ExamService $service,
    ){}

    /**
     * create exam data
     *
     * @param StoreExamRequest $request
     * @return JsonResponse
     */
    public function store(StoreExamRequest $request): JsonResponse
    {
        try {
            $data = $this->service->store($request);

            return ResponseHelper::success($data, message('alert.add_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * update exam data
     *
     * @param UpdateExamRequest $request
     * @param Exam $exam
     * @return JsonResponse
     */
    public function update(UpdateExamRequest $request, Exam $exam): JsonResponse
    {
        try {
            $data = $this->service->update($request, $exam);

            return ResponseHelper::success($data, message('alert.update_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }

    /**
     * delete exam data
     *
     * @param Exam $exam
     * @return JsonResponse
     */
    public function destroy(Exam $exam): JsonResponse
    {
        try {
            $is_success = $this->service->delete($exam);

            if ($is_success) {
                return ResponseHelper::success(null, message('alert.delete_success'));
            } else {
                return ResponseHelper::success(null, message('alert.delete_constrained'), Response::HTTP_CONFLICT);
            }
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.delete_failed', $th));
        }
    }

    /**
     * genarate token
     *
     * @param Exam $exam
     * @return JsonResponse
     */
    public function generateToken(Exam $exam): JsonResponse
    {
        try {
            $this->service->generateToken($exam);

            return ResponseHelper::success(null, 'Berhasil membuat token ujian.');
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }
}
