<?php

namespace App\Http\Controllers\Api\School;

use App\Exports\Student\StudentExport;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\School\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StudentController extends Controller
{
    public function __construct
    (
        private StudentService $service,
    ){}

    /**
     * export student group by classrooom
     * 
     * @param Request $request
     * @return BinaryFileResponse|JsonResponse
     */
    public function export(Request $request): BinaryFileResponse|JsonResponse
    {
        $sheets = $this->service->export($request);
        $school_year = $request->school_year;

        $fileName = 'DATA SISWA - ' . ($school_year 
            ? 'TAHUN AJARAN ' . str_replace("/", "-", $school_year)
            : 'SEMUA TAHUN AJARAN') . '.xlsx';

        if (empty($sheets)) {
            return ResponseHelper::error(message('alert.download_failed', 'Tidak ada kelas ' . 
                ($school_year 
                    ? 'pada tahun ajaran '  . $school_year
                    : 'di semua tahun ajaran')),
                Response::HTTP_NOT_FOUND);
        }

        try {
            return Excel::download(
                new StudentExport($sheets),
                $fileName,
                \Maatwebsite\Excel\Excel::XLSX,
                [
                    'Content-Disposition' => "attachment; filename=\"{$fileName}\"; filename*=UTF-8''" . rawurlencode($fileName),
                ]
            );
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.download_failed', $th));
        }
    }
}
