<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Student\SubjectController;
use App\Http\Controllers\Student\FeedbackController;
use App\Http\Controllers\Student\ViolationController;
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\Student\RepairStudentController;
use App\Http\Controllers\Student\DashboardStudentController;
use App\Http\Controllers\Student\ExamController;
use App\Http\Controllers\Student\StudentExamController;
use App\Http\Controllers\Student\ExamQuestionController;
use App\Http\Controllers\Student\GraduationController;
use App\Http\Controllers\Student\ModuleController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\TaskController;
use App\Http\Controllers\Student\TopicController;



Route::middleware('auth', 'role:student')->prefix('student')->name('student.')->group(function () {
    Route::get('', [DashboardStudentController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    // Route::get('/topics/{subject}', [TopicController::class, 'index'])->name('topic.index');


    Route::get('violations', [ViolationController::class, 'index'])->name('violations');
    Route::resource('repairs', RepairStudentController::class);
    Route::get('/class', [FeedbackController::class, 'index'])->name('feedback.index');

    Route::middleware('feedback.active')->group(function () {
        Route::post('feedback/{lessonSchedule}', [FeedbackController::class, 'store'])->name('feedback.store');
        Route::put('feedback/update/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');
    });
    Route::get('/all-feedback-schedule', [FeedbackController::class, 'show'])->name('feedback.show');
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance');

    Route::get('/e-learning', [SubjectController::class, 'index'])->name('e-learning.index');
    Route::get('modules/{module}', [ModuleController::class, 'show'])->name('material.detail');

    Route::get('topic', function () {
        return view('student.pages.topic.index');
    })->name('topic');

    Route::get('/task', [TaskController::class, 'index'])->name('tasks.index');

    Route::get('e-learning/{classroom}/{subject}', [TopicController::class, 'index'])->name('e-learning.detail');
    Route::get('task-detail/{task}', [TaskController::class, 'show'])->name('tasks.show');

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

    Route::prefix('graduations')->name('graduations.')->group(function () {
        Route::get('/', [GraduationController::class, 'index'])->name('index');
        Route::patch('/update-status', [GraduationController::class, 'update'])->name('update');
    });

    // ========================
    // UJIAN SISWA
    // ==========================
    Route::prefix('exams')->name('exams.')->middleware('web.session')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::get('/{exam}', [ExamController::class, 'show'])->name('detail');
        Route::get('/working/{exam}', [ExamQuestionController::class, 'show'])->name('working');
        Route::get('/{studentExam}/result', [StudentExamController::class, 'show'])->name('result');
    });
});
