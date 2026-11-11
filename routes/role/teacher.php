<?php

use App\Http\Controllers\Teacher\ModuleController;
use App\Http\Controllers\Teacher\TopicController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\MeetingController;
use App\Http\Controllers\Teacher\SubjectController;
use App\Http\Controllers\Teacher\AttendanceController;
use App\Http\Controllers\Teacher\TeacherJournalController;
use App\Http\Controllers\Teacher\PresenceCreateController;
use App\Http\Controllers\Teacher\PresenceDetailController;
use App\Http\Controllers\Teacher\TaskController;
use App\Http\Controllers\Teacher\TeacherModuleApiController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use App\Http\Controllers\Teacher\StudentFeedbackController;
use App\Http\Controllers\Teacher\DashboardTeacherController;
use App\Http\Controllers\Teacher\ExamQuestionController;
use App\Http\Controllers\Teacher\ExamRecapController;
use App\Http\Controllers\Teacher\ModuleController as TeacherModuleController;
use App\Http\Controllers\Teacher\ProfileController;
use App\Http\Controllers\Teacher\QuestionBankController;
use App\Http\Controllers\Teacher\StudentExamController;
use App\Http\Controllers\Teacher\TeacherMaterialController;
use App\Http\Controllers\Teacher\TeacherMaterialShowController;

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('', [DashboardTeacherController::class, 'index'])->name('dashboard');

    // extracurricular
    Route::get('extracurricular', function () {
        return view('teacher.pages.ekstrakulikuler.index');
    })->name('extracurricular.index');

    Route::get('material', function () {
        return view('teacher.pages.material.index');
    })->name('material.index');

    Route::get('modules/{id}', fn() => view('multi-role.page.modules.detail-material'))->name('material.detail');

    Route::resource('journals', TeacherJournalController::class)->except(['create', 'store', 'update', 'edit']);
    Route::get('journals/update/{teacherJournal}', [TeacherJournalController::class, 'edit'])->name('journals.edit');
    Route::put('journals/update/{teacherJournal}', [TeacherJournalController::class, 'update'])->name('journals.update');

    Route::get('journals/create/{lessonSchedule}', [TeacherJournalController::class, 'create'])->name('journals.create');
    Route::post('journals/create/{lessonSchedule}', [TeacherJournalController::class, 'store'])->name('journals.store');
    Route::resource('list-student-class', TeacherStudentController::class);


    Route::get('/show-module/{module}', [TeacherModuleController::class, 'show'])->name('module.show');

    Route::get('student-feedback', [StudentFeedbackController::class, 'index'])->name('student-feedback.index');

    Route::get('attendance/history', [AttendanceController::class, 'index'])->name('attendance.history');
    Route::get('presence/create/{teacherJournal}', PresenceCreateController::class)->name('presence.create');
    Route::get('presence/detail/{teacherJournal}', PresenceDetailController::class)->name('presence.detail');

    Route::get('value-recap', function () {
        return view('teacher.pages.value-recap.index');
    })->name('value-recap.index');

    Route::get('topic', function () {
        return view('teacher.pages.topic.index');
    })->name('topic.index');

    Route::get('material/create', [ModuleController::class, 'create'])->name('material.create');
    Route::get('material/show/{module}', [ModuleController::class, 'show'])->name('material.show');
    Route::get('material/edit/{module}', [ModuleController::class, 'edit'])->name('material.edit');

    Route::get('/preview/materi', function () {
        return view('partials.preview.index');
    })->name('preview.materi');

    Route::get('/preview/youtube', function () {
        return view('partials.preview.youtube');
    })->name('preview.youtube');

    Route::get('/preview/google', function () {
        return view('partials.preview.google-platform');
    })->name('preview.google');

    Route::get('/preview/editor', function () {
        return view('partials.preview.text-editor');
    })->name('preview.editor');

    Route::get('/evaluations', [TaskController::class, 'index'])->name('evaluation.index');
    Route::get('/evaluations/{classroom}/{subject}', [TaskController::class, 'showByClassroom'])->name('evaluation.detail');
    Route::get('e-learning', [SubjectController::class, 'index'])->name('e-learning.index');
    Route::get('e-learning/{classroom}/{subject}', [TopicController::class, 'index'])->name('e-learning.detail');
    Route::get('e-learning/grade-summary', [TaskController::class, 'index'])->name('e-learning.grade-summary');
    Route::prefix('task')->name('task.')->group(function () {
        Route::get('create/{topic}', [TaskController::class, 'create'])->name('create');
        Route::get('edit/{task}', [TaskController::class, 'edit'])->name('edit');
        Route::get('detail/{task}', [TaskController::class, 'show'])->name('detail');
    });

    Route::get('/evaluation/{task}', [TaskController::class, 'show']);
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');


    Route::get('question-bank', [QuestionBankController::class, 'index'])->name('question-bank.index');
    Route::get('question-bank/{questionBank}', [QuestionBankController::class, 'show'])->name('question-bank.detail');
    Route::get('question-bank/{questionBank}/create', [QuestionBankController::class, 'create'])->name('question-bank.create');
    Route::get('question-bank/{examQuestion}/edit', [QuestionBankController::class, 'edit'])->name('question-bank.edit');

    Route::get('exam-monitoring', [ExamRecapController::class, 'index'])
        ->name('exam-monitoring.index');

    Route::get('exam-monitoring/detail/{exam}', [ExamRecapController::class, 'show'])
        ->name('exam-monitoring.detail');
    Route::get('exam-monitoring/{exam}/classrooms/{classroom}', [ExamRecapController::class, 'examDetail'])
        ->name('exam-monitoring.classroom-detail');


    Route::get('exam-monitoring/student-detail/{studentExam}', [StudentExamController::class, 'show'])
        ->name('exam-monitoring.student-detail');


    // Download format
    Route::get('/exam-question/format/download', [ExamQuestionController::class, 'downloadTemplate'])->name('exam-question.download-format');
    Route::get('/task-question/format/download', [TaskController::class, 'downloadFormat'])->name('task-question.download-format');
});
