<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Api\Teacher\AttendanceJournalController as TeacherAttendanceJournalApiController;
use App\Http\Controllers\Api\RfidApiController;
use App\Http\Controllers\AttendanceMasterController;
use App\Http\Controllers\Api\AttendanceRuleApiController;
use App\Http\Controllers\Api\ExportStudentExamController;
use App\Http\Controllers\Api\LessonScheduleApiController;
use App\Http\Controllers\Api\LoginApiController;
use App\Http\Controllers\Api\School\ClassroomController;
use App\Http\Controllers\Api\School\ClassroomStudentController as SchoolClassroomStudentController;
use App\Http\Controllers\Api\School\ExamController;
use App\Http\Controllers\Api\School\ExamQuestionController as SchoolExamQuestionController;
use App\Http\Controllers\Api\School\QuestionBankController;
use App\Http\Controllers\Api\School\StudentController;
use App\Http\Controllers\Api\School\StudentExamController as SchoolStudentExamController;
use App\Http\Controllers\Api\School\SubjectController as SchoolSubjectController;
use App\Http\Controllers\Api\SchoolDetailController;
use App\Http\Controllers\Api\StafApiController;
use App\Http\Controllers\Api\Student\ExamController as StudentExamController;
use App\Http\Controllers\Api\Student\SubjectController as StudentSubjectApiController;
use App\Http\Controllers\Api\Student\TopicController as StudentTopicApiController;
use App\Http\Controllers\Api\Teacher\SubjectController as TeacherSubjectApiController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\StudentFeedbackController;
use App\Http\Controllers\Api\Teacher\TaskController as TeacherTaskController;
use App\Http\Controllers\Api\Teacher\TeacherJournalController as TeacherJournalApiController;
use App\Http\Controllers\Api\TeacherApiController;
use App\Http\Controllers\ClassroomStudentController;
use App\Http\Controllers\Schools\PermissionController;
use App\Http\Controllers\Api\Teacher\ModuleController as TeacherModuleApiController;
use App\Http\Controllers\Api\Student\ModuleController as StudentModuleApiController;
use App\Http\Controllers\Api\Student\ProfileController;
use App\Http\Controllers\Api\Student\TaskController as StudentTaskController;
use App\Http\Controllers\Api\Student\TaskSubmissionController as StudentTaskSubmissionController;
use App\Http\Controllers\Api\TaskSubmissionController;
use App\Http\Controllers\Api\Teacher\EvaluationController;
use App\Http\Controllers\Api\Teacher\ExamQuestionController as TeacherExamQuestionController;
use App\Http\Controllers\Api\Teacher\ProfileController as TeacherProfileController;
use App\Http\Controllers\Api\Teacher\TaskSubmissionController as TeacherTaskSubmissionController;
use App\Http\Controllers\Api\Teacher\TopicController as TeacherTopicController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Teacher\ExamQuestionChoiceController;
use App\Http\Controllers\Api\Teacher\ExamQuestionEssayController;
use App\Http\Controllers\Api\Teacher\ExamQuestionMatchingController;
use App\Http\Controllers\Api\Teacher\ExamQuestionOptionController;
use App\Http\Controllers\Api\Teacher\ExamRecapController;
use App\Models\ModelHasRfid;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('attendace/masterkey-check', [AttendanceMasterController::class, 'check'])->name('attendance-test.check');
Route::post('attendance/add', [AttendanceController::class, 'store'])->name('attendance.add');
Route::get('attendance/rfids', [RfidApiController::class, 'index'])->name('rfid.account');
Route::get('attendance/hours', [AttendanceRuleApiController::class, 'index'])->name('attendance.hour');
Route::get('attendance/list', [AttendanceController::class, 'listAttendance']);
Route::get('attendance/reset', [AttendanceController::class, 'reset']);
Route::get('school/detail', [SchoolDetailController::class, 'index']);
Route::get('student/classroom/{classroom}', [ClassroomStudentController::class, 'getByClasroom']);

Route::get('users-all', function () {
    $users = User::all();
    return response()->json($users);
});

Route::get('test-day-attendance', function () {
    $attendances = App\Models\Attendance::whereDay('created_at', now()->day)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->get();

    $users = ModelHasRfid::all();
    return response()->json([
        'attendance-count' => count($attendances),
        'user-count' => count($users),
        'attendance' => $attendances,
        'users' => $users
    ]);
});



Route::get('/run-command', function () {
    // Menjalankan command
    \Illuminate\Support\Facades\Artisan::call('command:create-attendance');

    // Opsional: Menampilkan output dari command
    $output = \Illuminate\Support\Facades\Artisan::output();

    // Kembalikan response ke pengguna
    return response()->json([
        'status' => 'success',
        'output' => $output,
    ]);
});

Route::post('login', [LoginApiController::class, 'login']);
Route::get('user-detail/{user}', [LoginApiController::class, 'user_detail']);

Route::get('feedback-active', [PermissionController::class, 'is_active']);


Route::name('api.')->group(function () {
    Route::prefix('/school')->name('school.')->middleware(['auth:sanctum', 'role:school'])->group(function () {
        Route::post('classroom-student/{classroom}', [SchoolClassroomStudentController::class, 'store'])->name('classroom-student.store');
        Route::delete('classroom-student', [SchoolClassroomStudentController::class, 'destroy'])->name('classroom-student.destroy');
        Route::apiResource('/question-bank', QuestionBankController::class)->except(['index']);
        Route::put('/activate-questions/{questionBank}', [SchoolExamQuestionController::class, 'activateQuestions'])->name('activate-questions');

        Route::resource('exams', ExamController::class)->only(['store', 'update', 'destroy']);

        Route::post('/exams/{studentExam}/reset', [SchoolStudentExamController::class, 'reset'])->name('exams.reset');
        Route::post('/exams/reset-many', [SchoolStudentExamController::class, 'resetMany'])->name('exams.reset-many');

        Route::get('classrooms/{levelClass}/{schoolYear}', [ClassroomController::class, 'getByLevelClassAndSchoolYear'])->name('classroom.by-class-level-and-school-year');
        Route::apiResource('/subject', SchoolSubjectController::class);

        Route::patch('/exams/{exam}/generate-token', [ExamController::class, 'generateToken'])->name('exams.generate-token');

        Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
    });

    Route::prefix('/student')->name('student.')->middleware(['auth:sanctum', 'role:student'])->group(function () {
        Route::get('/dashboard/{user}', [StudentApiController::class, 'index'])->name('dashboard');
        Route::get('/history-attendance/{user}', [StudentApiController::class, 'history_attendance'])->name('attendance.index');
        Route::get('/lesson-schedule/{user}', [StudentApiController::class, 'lessonSchedule'])->name('lesson-schedule.index');
        Route::get('/class-student/{user}', [StudentApiController::class, 'class_student'])->name('class-student.index');
        Route::get('/point-student/{user}', [StudentApiController::class, 'point_student'])->name('point.show');
        Route::get('/detail-profile/{user}', [StudentApiController::class, 'get_detail_profile'])->name('profile.show');
        Route::get('/violation/{user}', [StudentApiController::class, 'violation'])->name('violation.index');
        Route::get('/repair/{user}', [StudentApiController::class, 'repair'])->name('repair.index');
        Route::post('/feedback/{lessonSchedule}', [StudentFeedbackController::class, 'store'])->name('feedback.store');
        Route::put('/feedback/update/{feedback}', [StudentFeedbackController::class, 'update'])->name('feedback.update');

        // New ones (Need Sanctum)
        Route::get('/subjects', [StudentSubjectApiController::class, 'index'])->name('subject.index');

        Route::get('/tasks', [StudentTaskController::class, 'index'])->name('task.index');
        Route::get('/newest-modules', [StudentModuleApiController::class, 'index'])->name('module.show');
        Route::get('show-tasks/{task}', [StudentTaskController::class, 'show'])->name('task.show');
        Route::post('submit-tasks/{task}', [StudentTaskSubmissionController::class, 'storeOrUpdate'])->name('submission-task.storeOrUpdate');

        Route::get('/task-statistic', [StudentTaskController::class, 'statistic'])->name('task.statistic');
        Route::get('/latest-tasks', [StudentTaskController::class, 'getLatestStudentTasks'])->name('task.latest');

        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/delete-image', [ProfileController::class, 'deleteImage'])->name('profile.delete-image');

        Route::get('/topics/{subject}', [StudentTopicApiController::class, 'index'])->name('topic.index');

        Route::post('/exams/{exam}/start', [StudentExamController::class, 'start'])->name('exam.start');
        Route::post('/exams/{studentExam}/finish', [StudentExamController::class, 'finish'])->name('exam.finish');
    });

    Route::prefix('/staf')->name('staf.')->middleware(['role:staff'])->group(function () {
        Route::post('/create-journal/{user}', [StafApiController::class, 'create_journal'])->name('journal.store');
        Route::get('/dashboard/{user}', [StafApiController::class, 'index'])->name('dashboard');
        Route::get('/history-journals/{user}', [StafApiController::class, 'history_journals'])->name('journal.index');
        Route::get('/overview-header', [StafApiController::class, 'overview_header'])->name('overview-header');
        Route::get('/max-point', [StafApiController::class, 'max_point'])->name('max-point.show');
        Route::get('/list-violation', [StafApiController::class, 'list_violation'])->name('violation.index');
        Route::get('/list-repair', [StafApiController::class, 'list_repair'])->name('repair.index');
        Route::get('/list-point-student', [StafApiController::class, 'list_point_student'])->name('point-student.index');
        Route::get('/popular-violations', [StafApiController::class, 'popular_violations'])->name('');
        Route::get('/student-permissions', [StafApiController::class, 'student_permissions']);
        Route::get('/statistic-violation', [StafApiController::class, 'statistic_violation']);
    });

    Route::prefix('/teacher')->name('teacher.')->middleware(['role:teacher', 'auth:sanctum'])->group(function () {
        Route::get('/class/{user}', [TeacherApiController::class, 'class'])->name('class.index');
        Route::get('/attendance/{user}', [TeacherApiController::class, 'teacher_attendance'])->name('attendance.index');
        Route::get('/lesson-schedule/{user}', [TeacherApiController::class, 'today_lesson_schedule'])->name('lesson-schedule.index');
        Route::get('/history-journal/{user}', [TeacherApiController::class, 'today_history_journal'])->name('journal.today');
        Route::get('/all-history-journal/{user}', [TeacherApiController::class, 'all_history_journal'])->name('journal.all');
        Route::get('/subject/{user}', [TeacherApiController::class, 'teacher_subject'])->name('subject.index');
        Route::get('/get-feedback/{teacherSubject}', [TeacherApiController::class, 'get_feedback'])->name('feedback.index');


        Route::post('/topics/{topic}/modules', [TeacherModuleApiController::class, 'store'])->name('module.store');
        Route::get('/modules/{module}', [TeacherModuleApiController::class, 'show'])->name('module.show');
        Route::put('/modules/{module}', [TeacherModuleApiController::class, 'update'])->name('module.update');
        Route::delete('/modules/{module}', [TeacherModuleApiController::class, 'destroy'])->name('module.destroy');

        Route::post('/store-journal/{lessonSchedule}', [LessonScheduleApiController::class, 'store'])->name('journal.store');
        Route::post('/store-meeting/{lessonSchedule}/{meeting}', [TeacherJournalApiController::class, 'store'])->name('meeting.store');
        Route::put('/update-meeting/{teacherJournal}', [TeacherJournalApiController::class, 'update'])->name('meeting.update');

        Route::get('/detail-journal/{lessonSchedule}', [LessonScheduleApiController::class, 'show'])->name('journal.show');
        Route::put('/update-journal/{lessonSchedule}', [LessonScheduleApiController::class, 'update'])->name('journal.update');


        // Route untuk e-learning (jika belum ada)
        Route::get('e-learning', function () {
            // Ganti dengan controller yang sesuai
            return view('teacher.pages.e-learning.index');
        })->name('e-learning.index');


        // New ones (Need Sanctum)
        Route::get('topics/{subject}/{classroom}', [TeacherTopicController::class, 'index'])->name('topic.index');
        Route::post('topics/{subject}/{classroom}', [TeacherTopicController::class, 'store'])->name('topic.store');
        Route::resource('topics', TeacherTopicController::class)->only('update', 'destroy');

        Route::get('/subjects', [TeacherSubjectApiController::class, 'index'])->name('subjects.index'); // terdapat 2 endpoint subject yg hampir sama

        Route::post('/store-tasks/{topic}', [TeacherTaskController::class, 'store'])->name('task.store');
        Route::get('/show-tasks/{task}', [TeacherTaskController::class, 'show'])->name('task.show');
        Route::put('/update-tasks/{task}', [TeacherTaskController::class, 'update'])->name('task.update');
        Route::delete('/delete-tasks/{task}', [TeacherTaskController::class, 'destroy'])->name('task.destroy');

        Route::post('/update-assessment-values', [TeacherTaskSubmissionController::class, 'update'])->name('update.assessment-value');
        Route::post('/update-assessment-notes/{taskSubmission}', [TeacherTaskSubmissionController::class, 'updateNote'])->name('update.assessment-note');

        Route::get('/detail-student-attendances/{teacherJournal}', [TeacherAttendanceJournalApiController::class, 'show'])->name('student.index');
        Route::post('/store-student-attendances/{teacherJournal}', [TeacherAttendanceJournalApiController::class, 'store'])->name('student-attendance.store');
        Route::put('/update-student-attendances/{teacherJournal}', [TeacherAttendanceJournalApiController::class, 'update'])->name('student-attendance.update');

        // Route::get('/exam-questions', [ExamQuestionController::class, 'index'])->name('exam-question.index');

        Route::put('/profile', [TeacherProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/delete-image', [TeacherProfileController::class, 'deleteImage'])->name('profile.delete-image');

        Route::get('/download-all-answers/{task}', [EvaluationController::class, 'export'])->name('task-submission.export-by-task');
        Route::get('/task-recap-export/{classroom}/{subject}', [EvaluationController::class, 'exportMany'])->name('task-submission.export-many');
        Route::get('/task-recap-export-all', [EvaluationController::class, 'exportAll'])->name('task-submission.export-all');

        Route::post('/exam-question/{questionBank}', [ExamQuestionChoiceController::class, 'store'])->name('exam-question.create');
        Route::put('/exam-question/{examQuestion}/update', [ExamQuestionChoiceController::class, 'update'])->name('exam-question.update');
        Route::post('/exam-question/{questionBank}/import', [TeacherExamQuestionController::class, 'import'])->name('exam-question.import');
        Route::delete('/exam-question/{examQuestion}', [TeacherExamQuestionController::class, 'destroy'])->name('exam-question.destroy');

        Route::post('/exam-questions/{questionBank}/copy', [TeacherExamQuestionController::class, 'copyQuestions'])->name('exam-question.copy');

        Route::delete('/exam-question/option/delete', [ExamQuestionOptionController::class, 'destroy'])->name('exam-question.option.delete');

        // Essay
        Route::post('/exam-question/essay/{questionBank}', [ExamQuestionEssayController::class, 'store'])->name('exam-question.essay.store');
        Route::put('/exam-question/essay/{examQuestion}', [ExamQuestionEssayController::class, 'update'])->name('exam-question.essay.update');
        Route::get('/exam-question/essay/{studentExam}', [ExamQuestionEssayController::class, 'show'])->name('exam-question.show');
        Route::put('/exam-question/essay/{studentQuestionAnswer}/grading', [ExamQuestionEssayController::class, 'grading'])->name('exam-question.essay.grading');

        // Matching
        Route::post('/exam-question/matching/{questionBank}', [ExamQuestionMatchingController::class, 'store'])->name('exam-question.matching.store');
        Route::put('/exam-question/matching/{examQuestion}', [ExamQuestionMatchingController::class, 'update'])->name('exam-question.matching.update');

        // Exams
        Route::get('exam-monitoring', [ExamRecapController::class, 'index'])->name('exam-monitoring.index');
        Route::get('exam-monitoring/detail/{exam}', [ExamRecapController::class, 'show'])->name('exam-monitoring.detail');
        Route::get('exam-monitoring/{exam}/classrooms/{classroom}', [ExamRecapController::class, 'examDetail'])->name('exam-monitoring.classroom-detail');
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/detail-module/{module}', [TeacherModuleApiController::class, 'show'])->name('module.show');
        Route::get('/meeting/{classroom}/{subject}', [TeacherJournalApiController::class, 'getMeetings'])->name('meeting.index');
        Route::put('/update-password', [ResetPasswordController::class, 'updatePassword'])->name('update.password');
        Route::get('/download-submission-task/{taskSubmission}', [TaskSubmissionController::class, 'downloadFile'])->name('task-submission.download');

        Route::get('/exams/{exam}/{classroom}/export', [ExportStudentExamController::class, 'export'])->name('exams.export');
        Route::get('/exams/export-all', [ExportStudentExamController::class, 'exportAll'])->name('exams.export-all');
    });
});
