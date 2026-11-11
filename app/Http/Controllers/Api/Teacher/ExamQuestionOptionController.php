<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\ExamQuestionOptionRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\ExamQuestionOptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamQuestionOptionController extends Controller
{
    public function __construct
    (
        private ExamQuestionOptionRepository $examQuestionOption,
    ){}

    /**
     * bulk force delete exam question option
     *
     * @param ExamQuestionOptionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ExamQuestionOptionRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->examQuestionOption->deleteWhereIn($request->ids);

            DB::commit();
            return ResponseHelper::success($request->ids, message('alert.delete_success'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponseHelper::error(message('alert.delete_failed', $th));
        }   
    }
}
