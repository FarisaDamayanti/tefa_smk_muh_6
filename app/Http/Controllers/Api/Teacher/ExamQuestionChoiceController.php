<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ExamQuestionRequest;
use App\Http\Requests\Teacher\StoreExamQuestionChoiceRequest;
use App\Http\Requests\Teacher\UpdateExamQuestionChoiceRequest;
use App\Models\ExamQuestion;
use App\Models\QuestionBank;
use App\Services\Teacher\ExamQuestionChoiceService;
use Illuminate\Http\Request;

class ExamQuestionChoiceController extends Controller
{
    public function __construct
    (
        private ExamQuestionChoiceService $service,
    ){}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamQuestionChoiceRequest $request, QuestionBank $questionBank)
    {
        try {
            $this->service->store($request, $questionBank);

            return ResponseHelper::success($request->validated(), message('alert.add_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExamQuestionChoiceRequest $request, ExamQuestion $examQuestion)
    {
        try {
            $this->service->update($request, $examQuestion);

            return ResponseHelper::success($request->validated(), message('alert.update_success'));
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }
}
