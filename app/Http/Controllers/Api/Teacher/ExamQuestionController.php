<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Contracts\Repositories\ExamQuestionMatchingAnswerRepository;
use App\Contracts\Repositories\ExamQuestionMatchingRepository;
use App\Contracts\Repositories\ExamQuestionOptionRepository;
use App\Contracts\Repositories\ExamQuestionRepository;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\School\ImportExamQuestionRequest;
use App\Http\Requests\Teacher\ExamQuestionCopyRequest;
use App\Http\Requests\Teacher\ExamQuestionRequest;
use App\Imports\ExamQuestionImport;
use App\Imports\ExamQuestionImportV2;
use App\Models\QuestionBank;
use App\Services\Teacher\ExamQuestionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ExamQuestionController extends Controller
{
    public function __construct(
        private ExamQuestionRepository $examQuestion,
        private ExamQuestionMatchingRepository $examQuestionMatching,
        private ExamQuestionMatchingAnswerRepository $examQuestionMatchingAnswer,
        private ExamQuestionOptionRepository $examQuestionOption,

        private ExamQuestionService $examQuestionService
    ) {}

    /**
     * Update the specified resource in storage.
     */
    public function updateOrCreate(ExamQuestionRequest $request, QuestionBank $questionBank, ?string $examQuestion = null)
    {
        $isUpdate = $request->isUpdate();

        try {
            $this->examQuestionService->handleUpdateOrCreate($request, $questionBank, $examQuestion);
            return ResponseHelper::success(null, message( $isUpdate? 'alert.update_success' :'alert.add_success'));
        } catch(HttpException $ht){
            return ResponseHelper::error(message($isUpdate? 'alert.update_failed' :'alert.add_failed', $ht), $ht->getStatusCode());
        } catch (Throwable $th) {
            return ResponseHelper::error(message($isUpdate? 'alert.update_failed' :'alert.add_failed', $th));
        }
    }

    public function import(ImportExamQuestionRequest $request, QuestionBank $questionBank)
    {
        DB::beginTransaction();
        try {
            $import = new ExamQuestionImport($questionBank, auth_employee()->id, [
                'examQuestion' => $this->examQuestion,
                'examQuestionMatching' => $this->examQuestionMatching,
                'examQuestionMatchingAnswer' => $this->examQuestionMatchingAnswer,
                'examQuestionOption' => $this->examQuestionOption,
                'examQuestionService' => $this->examQuestionService
            ]);

            Excel::import($import, $request->file('file'));
            DB::commit();
            if(!empty($import->errors)){
                return ResponseHelper::success(['success' => $import->success, 'failed' => $import->failed, 'errors' =>  $import->errors], "Berhasil menambah {$import->success} soal, dan {$import->failed} gagal.", Response::HTTP_MULTI_STATUS);
            }
            return ResponseHelper::success(['success' => $import->success, 'failed' => $import->failed, 'errors' =>  $import->errors], message('alert.add_success'));
        } catch (HttpException $ht){
            DB::rollBack();
            return ResponseHelper::error(message('alert.add_failed', $ht), $ht->getStatusCode());
        } catch (Throwable $th) {
            DB::rollBack();
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->examQuestion->delete($id);
            return ResponseHelper::success(null, message('alert.delete_success'));
        } catch (Throwable $th) {
            return ResponseHelper::error(message('alert.delete_failed', $th));
        }
    }

    /**
     * Handle exam questions copy to another question bank
     *
     * @param ExamQuestionCopyRequest $request
     */
    public function copyQuestions(ExamQuestionCopyRequest $request, QuestionBank $questionBank)
    {
        DB::beginTransaction();
        try {
            $this->examQuestionService->handleExamQuestionCopy($request, $questionBank);
            DB::commit();
            return ResponseHelper::success(null, message('alert.add_success'));
        } catch(HttpException $ht){
            return ResponseHelper::error(message('alert.add_failed', $ht), $ht->getStatusCode());
        } catch (Throwable $th) {
            DB::rollBack();
            return ResponseHelper::error(message('alert.add_failed', $th));
        }
    }
}
