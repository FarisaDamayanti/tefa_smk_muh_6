<?php

namespace App\Http\Controllers\Api\School;

use App\Contracts\Repositories\QuestionBankRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\School\QuestionBankRequest;
use App\Http\Resources\School\QuestionBankDetailResource;
use App\Services\School\QuestionBankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class QuestionBankController extends Controller
{
    public function __construct(
        private QuestionBankRepository $questionBank,

        private QuestionBankService $questionBankService
    ){}

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionBankRequest $request)
    {
        $data = $request->validated();

        try {
            $this->questionBank->store($data);
            return ResponseHelper::success(null, message('alert.add_success'));
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $data = $this->questionBank->showDetailSearch($request, $id);
            return ResponseHelper::success(QuestionBankDetailResource::make($data), message('alert.fetch_success'));
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.fetch_failed', $th));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuestionBankRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $this->questionBankService->update($request, $id);
            DB::commit();
            return ResponseHelper::success(null, message('alert.update_success'));
        } catch (Throwable $th) {
            DB::rollBack();
            return ResponseHelper::error(message('alert.update_failed', $th));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->questionBankService->delete($id);
            return ResponseHelper::success(null,message('alert.delete_success'));
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.delete_failed', $th));
        }
    }
}
