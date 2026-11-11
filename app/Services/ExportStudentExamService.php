<?php

namespace App\Services;

use App\Contracts\Repositories\ExamRepository;
use App\Contracts\Repositories\LevelClassRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Enums\UserExamStatus;
use App\Exports\Exam\StudentExamExport;
use App\Helpers\ResponseHelper;
use App\Models\Classroom;
use App\Models\Exam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class ExportStudentExamService
{
    public function __construct
    (
        private ExamRepository $exam,
        private SubjectRepository $subject,
        private LevelClassRepository $levelClass,
        private SchoolYearRepository $schoolYear,
    ){}
    
    /**
     * mapping data for export excel
     *
     * @param Exam $exam
     * @param Classroom|null $classroom
     * @return array
     */
    public function mappingDataExport(Exam $exam, ?Classroom $classroom = null): array
    {
        $exam->loadMissing([
            'studentExams.student',
            'examClassrooms.classroom.levelClass',
            'examClassrooms.classroom.classroomStudents.student.user'
        ]);
        
        $classrooms = $classroom ? collect([$classroom]) : $exam->examClassrooms->pluck('classroom')->filter();

        $studentExamMap = $exam->studentExams->keyBy('student_id');

        $sheets = [];
        
        $now = now();
        $examEndTime = $exam->getExamEndTime();

        foreach ($classrooms as $classroom) {
            $classroom->loadMissing([
                'levelClass',
                'classroomStudents.student.user',
            ]);

            $students = $classroom->classroomStudents
                ->sortBy(fn($cs) => strtolower($cs->student->user->name))
                ->values();

            $rows = [];

            foreach ($students as $classroomStudent) {

                $student = $classroomStudent->student;
                $studentExam = $studentExamMap->get($student->id);

                $rows[] = [
                    'nisn'  => $student->nisn,
                    'name'  => $student->user->name,
                    'class' => $classroom->levelClass->name . ' ' . $classroom->name,
                    'score' => $studentExam->score ?? 0,
                    'status' => $studentExam
                        ? match ($studentExam->status) {
                            UserExamStatus::ON_PROGRESS->value => 'Sedang Mengerjakan',
                            UserExamStatus::GRADING->value => 'Belum Dinilai',
                            UserExamStatus::PASSED->value => 'Lulus',
                            UserExamStatus::NOT_PASSED->value => 'Tidak Lulus',
                        }
                        : ($now->isBefore($examEndTime) ? 'Belum Mengerjakan' : 'Tidak Mengerjakan'),
                ];
            }

            $sheets[] = [
                'sheet_name' => $classroom->levelClass->name . ' ' . $classroom->name,
                'title'      => $exam->title . ' ' . $classroom->levelClass->name . ' ' . $classroom->name,
                'rows'       => $rows,
            ];
        }

        return [
            'sheets' => $sheets,
        ];
    }

    /**
     * export all exam data
     *
     * @param Request $request
     * @return BinaryFileResponse|JsonResponse
     */
    public function exportAll(Request $request): BinaryFileResponse|JsonResponse
    {
        $request->merge(['export' => true]);
        $exams = $this->exam->search($request);

        $tempDir = storage_path('app/temp-export');
        File::ensureDirectoryExists($tempDir);

        $zipName = 'DAFTAR-NILAI-UJIAN-' . now()->format('Y-m-d');
        $zipPath = storage_path('app/' . $zipName);

        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $addFiles = 0;

        $filtering = [
            'subject' => $request->subject_id
                ? $this->subject->show($request->subject_id)?->name
                : 'Semua',

            'level_class' => $request->level_class_id
                ? $this->levelClass->show($request->level_class_id)?->name
                : 'Semua',

            'school_year' => $request->school_year_id
                ? $this->schoolYear->show($request->school_year_id)?->school_year
                : 'Semua',

            'semester' => $request->semester
                ? ucfirst($request->semester)
                : 'Semua',
        ];

        $folderName = $zipName;

        foreach ($exams as $exam) {
            $data = $this->mappingDataExport($exam);

            if (!empty($data['sheets'])) {
                $fileName = $exam->title . '.xlsx';
                $relativePath = 'temp-export/' . $fileName;
                $absolutePath = storage_path('app/' . $relativePath);
    
                Excel::store(
                    new StudentExamExport($data['sheets'], $filtering),
                    $relativePath,
                    'local'
                );
    
                if (!File::exists($absolutePath)) {
                    continue;
                }
    
                $zip->addFile($absolutePath, $folderName . '/' . $fileName);
    
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
