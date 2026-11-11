<?php

namespace App\Services\Teacher;

use App\Contracts\Repositories\ClassroomStudentRepository;
use App\Contracts\Repositories\LessonScheduleRepository;
use App\Contracts\Repositories\SchoolRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Contracts\Repositories\TaskRepository;
use App\Contracts\Repositories\TaskSubmissionRepository;
use App\Contracts\Repositories\TopicRepository;
use App\Enums\SubmissionStatusEnum;
use App\Exports\Evaluation\TaskRecapReportExport;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Teacher\EvaluationRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class EvaluationService
{
    public function __construct
    (
        private TaskSubmissionRepository $taskSubmission,
        private TaskRepository $task,
        private SchoolRepository $school,
        private LessonScheduleRepository $lessonSchedule,
        private SubjectRepository $subject,
        private TopicRepository $topic,
        private ClassroomStudentRepository $classroomStudent,
    ) {}

    /**
     * update grade student multiple
     *
     * @param EvaluationRequest $request
     * @return void
     */
    public function update(EvaluationRequest $request): void
    {
        $data = $request->validated()['evaluation'];

        $ids = [];
        $cases = 'CASE id ';

        foreach ($data as $item) {
            $ids[] = $item['id'];
            $cases .= "WHEN {$item['id']} THEN {$item['grade']} ";
        }

        $cases .= 'END';

        $this->taskSubmission->bulkUpdate($ids, ['grade' => $cases]);
    }

    /**
     * mapping data task submission for export excel 
     *
     * @param Collection $topics
     * @param Collection $students
     * @return Collection
     */
    public function getDataForEvaluation(Collection $topics, Collection $students): Collection
    {
        $school = $this->school->first();
        $topicTasks = [];

        $mapped = $topics->map(function ($topic) use ($students, $school, &$topicTasks) {
            $tasks = $this->task->getByTopicWithSubmission($topic->id);
            $topicTasks[$topic->id] = [
                'topic' => $topic,
                'tasks' => $tasks
            ];
            
            $studentsSubmission = $students->map(function ($student) use (&$tasks, $school) {
                $submits = $tasks->map(function ($task, $index) use ($student) {
                    $submitted = $task->latestTaskSubmission->first(fn($lts) => $lts->student_id == $student->student_id);

                    $task->task_no = $index + 1;

                    if ($submitted) {
                        $status = SubmissionStatusEnum::compute($submitted->status, $task->allow_overdue, $submitted->grade);
                        $submitted->task_title = $task->title;
                        $submitted->task_no = ($index + 1);
                        // $submitted->grade ??= $status;
                        $submitted->status = $status;
                        return $submitted;
                    }

                    return collect([
                        'task_no' => $index + 1,
                        'task_title' => $task->title,
                        'grade' => '-',
                        'status' => SubmissionStatusEnum::NOT_SUBMITTED
                    ]);
                });

                $topic = $tasks->first()?->topic;

                return collect([
                    'student' => $student->student,
                    'school' => $school,
                    'classroom' => $topic?->classroom,
                    'school_year' => $topic?->classroom->schoolYear,
                    'subject' => $topic?->subject,
                    'task_submissions' => $submits,
                ]);
            });

            $topic->studentsSubmission = $studentsSubmission;
            return $topic;
        });

        $mapped->topicTasks = $topicTasks;
        return $mapped;
    }

    /**
     * handle download / export multiple 
     *
     * @param Task $task
     * @return BinaryFileResponse|RedirectResponse
     */
    public function handleDownloadZipped(Task $task): BinaryFileResponse|JsonResponse
    {
        $submissions = $this->taskSubmission->getByTask($task->id);

        if ($submissions->isEmpty()) {
            return ResponseHelper::success(null, message('alert.download_failed', 'Belum ada siswa yang mengumpulkan'), Response::HTTP_NOT_FOUND);
        }

        $zipFileName = 'task-submissions-' . $task->id . '-' . now()->format('Y-m-d-H-i-s') . '.zip';
        $tempPath = storage_path('temp/' . $zipFileName);

        if (!File::exists(dirname($tempPath))) {
            File::makeDirectory(dirname($tempPath), 0755, true);
        }

        $zip = new ZipArchive;

        if ($zip->open($tempPath, ZipArchive::CREATE) === TRUE) {
            foreach ($submissions as $submission) {
                $filePath = $submission->answer;

                $userName =  $submission->student->user->name . '_' .  $task->title;

                if (Storage::exists($filePath)) {
                    $fileContent = Storage::get($filePath);

                    $fileName = basename($filePath);
                    $uniqueFileName = $userName . '-' . $fileName;

                    $zip->addFromString($uniqueFileName, $fileContent);
                } else if (str_starts_with($filePath, 'http')) {
                    $uniqueFileName = $userName . $submission->id . '-link.txt';
                    $fileContent = "Tautan jawaban: " . $filePath;

                    $zip->addFromString($uniqueFileName, $fileContent);
                }
            }

            $zip->close();
        }

        $headers = [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"',
        ];

        return response()->download($tempPath, $zipFileName, $headers)->deleteFileAfterSend(true);
    }

    /**
     * export all task submissions in all tasks in all topics by all teacher lesson schedule
     *
     * @param Request $request
     * @return BinaryFileResponse|JsonResponse
     */
    public function exportAll(Request $request): BinaryFileResponse|JsonResponse
    {
        $lessonSchedules = $this->lessonSchedule->getByEmployee(auth_employee()->id, $request);
        $subjects = $this->subject->whereTeacherSearch(auth_employee()->id, $request);

        if($lessonSchedules->isEmpty() || $subjects->isEmpty()) {
            return ResponseHelper::success(null, message('alert.download_failed', 'Data tidak tersedia'), Response::HTTP_NOT_FOUND);
        }

        $tempDir = storage_path('app/temp-export');
        File::ensureDirectoryExists($tempDir);

        $zipName = 'rekap-tugas-' . now()->format('YmdHis') . '.zip';
        $zipPath = storage_path('app/' . $zipName);

        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $addFiles = 0;

        foreach($lessonSchedules as $lessonSchedule) {
            foreach($subjects as $subject) {
                $topics = $this->topic->getByClassroomAndSubject($lessonSchedule->classroom_id, $subject->id, $request);

                if ($topics->isEmpty()) {
                    continue;
                }

                $students = $this->classroomStudent->getByClassId($lessonSchedule->classroom_id);

                if ($students->isEmpty()) {
                    continue;
                }

                $tasks = $this->getDataForEvaluation($topics, $students);

                $classroomName = Str::slug($lessonSchedule->classroom->levelClass->name . '-' . $lessonSchedule->classroom->name);
                $subjectName   = Str::slug($subject->name);

                $fileName = "{$classroomName}_{$subjectName}.xlsx";
                $relativePath = 'temp-export/' . $fileName;
                $absolutePath = storage_path('app/' . $relativePath);

                // ⬅️ SIMPAN, BUKAN DOWNLOAD
                Excel::store(
                    new TaskRecapReportExport($tasks),
                    $relativePath,
                    'local'
                );

                // ⬅️ MASUKKAN KE ZIP
                if (!File::exists($absolutePath)) {
                    continue; // atau throw Exception jika mau strict
                }

                // MASUKKAN KE ZIP
                $zip->addFile($absolutePath, $fileName);

                $addFiles++;
            }
        }

        $zip->close();

        if ($addFiles === 0) {
            File::delete($zipPath);
            return ResponseHelper::success(null, 'Tidak ada file yang dapat diexport', Response::HTTP_NOT_FOUND);
        }

        return response()
            ->download($zipPath)
            ->deleteFileAfterSend(true);
    }
}
