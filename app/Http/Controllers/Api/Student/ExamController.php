<?php

namespace App\Http\Controllers\Api\Student;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StartExamRequest;
use App\Http\Requests\Student\StoreExamRequest;
use App\Jobs\Exam\CalculateExamScoreJob;
use App\Models\Exam;
use App\Models\StudentExam;
use App\Services\Student\ExamService;
use Illuminate\Auth\Access\AuthorizationException;
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
     * start exam
     *
     * @param StartExamRequest $request
     * @param Exam $exam
     * @return JsonResponse
     */
    public function start(StartExamRequest $request, Exam $exam): JsonResponse
    {
        try {
            if (is_null($exam->token)) {
                return ResponseHelper::success($request->validated(), 'Token belum tersedia.',  Response::HTTP_NOT_FOUND);
            }

            if ($exam->token && $request->token === $exam->token) {
                $this->authorize('canStart', $exam);
                $data = $this->service->start($exam);

                return ResponseHelper::success($data, message('alert.fetch_success'));
            } else {
                return ResponseHelper::success($request->validated(), 'Token salah.',  Response::HTTP_UNAUTHORIZED);
            }
        } catch (AuthorizationException $e) {
            return ResponseHelper::error($e->getMessage(), Response::HTTP_FORBIDDEN);
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }

    /**
     * finish exam
     *
     * @param StoreExamRequest $request
     * @param StudentExam $studentExam
     * @return JsonResponse
     */
    public function finish(StoreExamRequest $request, StudentExam $studentExam): JsonResponse
    {
        try {
            $exam = $studentExam->exam;
            $data = $request->validated();

            $this->authorize('canAccess', $exam);
            CalculateExamScoreJob::dispatch(
                $studentExam,
                $exam,
                isset($data['questions']) ? $data['questions'] : [],
            );

            return ResponseHelper::success(null, message('alert.finish_exam_success', 'Jawaban sedang diproses'));
        } catch (AuthorizationException $e) {
            return ResponseHelper::error($e->getMessage(), Response::HTTP_FORBIDDEN);
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.finish_exam_failed', $th));
        }
    }
}
