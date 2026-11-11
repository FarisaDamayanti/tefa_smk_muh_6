<?php

namespace App\Http\Controllers\Api\School;

use App\Contracts\Repositories\ExamQuestionRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\ActivateExamQuestionRequest;
use App\Models\QuestionBank;
use App\Services\School\ExamQuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ExamQuestionController extends Controller
{
    public function __construct
    (
        private ExamQuestionService $examQuestionService,
    ){}

    /**
     * Activated question inside exams.
     */
    public function activateQuestions(ActivateExamQuestionRequest $request, QuestionBank $questionBank)
    {
        DB::beginTransaction();
        try {
            $activated = $this->examQuestionService->activateQuestion($request, $questionBank);
            DB::commit();
            return ResponseHelper::success($activated, message('alert.update_success'));
        } catch (Throwable $th) {
            DB::rollBack();
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }

}
