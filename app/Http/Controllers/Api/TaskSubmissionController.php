<?php

namespace App\Http\Controllers\Api;

use App\Enums\SubmissionTypeEnum;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\TaskSubmission;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedJsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TaskSubmissionController extends Controller
{
    /**
     * Undocumented function
     *
     * @param TaskSubmission $taskSubmission
     * @return JsonResponse|BinaryFileResponse|StreamedJsonResponse
     */
    public function downloadFile(TaskSubmission $taskSubmission): JsonResponse|BinaryFileResponse|StreamedResponse
    {
        if ($taskSubmission->task->submission_type->value == SubmissionTypeEnum::LINK->value) {
            return ResponseHelper::error(message('alert.download_failed', 'Tipe pengumpulan bukan berupa file'));
        }

        try {
            $filePath = storage_path('app/public/' . $taskSubmission->answer);
            $originalFileName = basename($taskSubmission->answer); 
            
            if (!file_exists($filePath) || str_starts_with($taskSubmission->answer, 'http')) {
                $fileName = $taskSubmission->student->user->name . '_' . $taskSubmission->task->title;
                $uniqueFileName = $fileName . '_' . $taskSubmission->id . '-link.txt';
                $fileContent = "Tautan jawaban: " . $taskSubmission->answer;
                
                return response()->streamDownload(function () use ($fileContent) {
                    echo $fileContent;
                }, $uniqueFileName);
            }

            return response()->download($filePath, $originalFileName);
        } catch (\Throwable $th) {
            return ResponseHelper::error(message('alert.download_failed', $th));
        }
    }
}
