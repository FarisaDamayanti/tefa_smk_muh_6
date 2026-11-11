<?php

namespace App\Http\Controllers\Api;

use App\Exports\Exam\StudentExamExport;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Exam;
use App\Services\ExportStudentExamService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportStudentExamController extends Controller
{
    public function __construct
    (
        private ExportStudentExamService $service,
    ){}
    
    /**
     * export excel by exam
     *
     * @param Exam $exam
     * @param Classroom $classroom
     * @return BinaryFileResponse|JsonResponse
     */
    public function export(Exam $exam, Classroom $classroom): BinaryFileResponse|JsonResponse
    {
        try {
            $data = $this->service->mappingDataExport($exam, $classroom);

            return Excel::download(
                new StudentExamExport($data['sheets']),
                $data['sheets'][0]['title'] . '.xlsx'
            );
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.download_failed', $th));
        }
    }

    /**
     * export excel all exam
     *
     * @param Request $request
     * @return BinaryFileResponse|JsonResponse
     */
    public function exportAll(Request $request): BinaryFileResponse|JsonResponse
    {
        try {
            return $this->service->exportAll($request);
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.download_failed', $th));
        }
    }
}
