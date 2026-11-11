<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\ExamQuestionRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreExamQuestionMatchingRequest;
use App\Http\Requests\Teacher\UpdateExamQuestionMatchingRequest;
use App\Models\QuestionBank;
use App\Services\Teacher\ExamQuestionMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ExamQuestionMatchingController extends Controller
{
    public function __construct(
        private ExamQuestionRepository $examQuestion,
        private ExamQuestionMatchingService $service
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamQuestionMatchingRequest $request, QuestionBank $questionBank)
    {
        DB::beginTransaction();
        try {
            $this->service->store($request, $questionBank);
            DB::commit();
            return ResponseHelper::success(null, message('alert.add_success'));
        } catch (HttpException $ht){
            return ResponseHelper::error(message('alert.add_failed', $ht), $ht->getStatusCode());
        } catch (Throwable $th) {
            DB::rollBack();
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    public function update(UpdateExamQuestionMatchingRequest $request, string $examQuestion)
    {
        DB::beginTransaction();
        try {
            $this->service->update($request, $examQuestion);
            DB::commit();
            return ResponseHelper::success(null, message('alert.add_success'));
        } catch (Throwable $th) {
            DB::rollBack();
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }
}
