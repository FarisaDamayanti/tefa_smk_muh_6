<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\ExamQuestionRepository;
use App\Contracts\Repositories\StudentQuestionAnswerRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ExamQuestionEssayRequest;
use App\Models\QuestionBank;
use App\Services\Teacher\ExamQuestionEssayService;
use App\Http\Requests\Teacher\GradingExamQuestionEssayRequest;
use App\Http\Resources\Teacher\StudentQuestionAnswerResource;
use App\Models\StudentQuestionAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ExamQuestionEssayController extends Controller
{
    public function __construct(
        private ExamQuestionRepository $examQuestion,
        private StudentQuestionAnswerRepository $studentQuestionAnswer,
        private ExamQuestionEssayService $service,
    ) {}

    /**
     * Show student answer essay by student exam id
     *
     * @param string $studentExam
     * @return JsonResponse
     */
    public function show(string $studentExam): JsonResponse
    {
        try {
            $rawData = $this->studentQuestionAnswer->getEssayByStudent($studentExam);
            $data = StudentQuestionAnswerResource::collection($rawData);
            
            return ResponseHelper::success($data, message('alert.fetch_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param ExamQuestionEssayRequest $request
     * @param QuestionBank $questionBank
     * @return JsonResponse
     */
    public function store(ExamQuestionEssayRequest $request, QuestionBank $questionBank): JsonResponse
    {
        try {
            $this->service->store($request, $questionBank);
            return ResponseHelper::success(null, message('alert.add_success'));
        } catch (HttpException $ht){
            return ResponseHelper::error(message('alert.add_failed', $ht), $ht->getStatusCode());
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param ExamQuestionEssayRequest $request
     * @param string $examQuestion
     * @return JsonResponse
     */
    public function update(ExamQuestionEssayRequest $request, string $examQuestion): JsonResponse
    {
        $data = $request->validated();
        try {
            $this->examQuestion->update($examQuestion, $data);
            return ResponseHelper::success(null, message('alert.add_success'));
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * update score student question answer (grading)
     *
     * @param GradingExamQuestionEssayRequest $request
     * @param StudentQuestionAnswer $studentQuestionAnswer
     * @return JsonResponse
     */
    public function grading(GradingExamQuestionEssayRequest $request, StudentQuestionAnswer $studentQuestionAnswer): JsonResponse
    {
        try {
            $data = $request->validated();
            $this->service->grading($request, $studentQuestionAnswer);

            return ResponseHelper::success($data, message('alert.update_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }
}
