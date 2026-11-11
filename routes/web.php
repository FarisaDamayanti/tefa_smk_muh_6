<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ClassroomStudentController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.custom-request');
Route::put('/update-password', [ResetPasswordController::class, 'updatePassword'])->name('update.password');

// Route::get('/', [LandingController::class, 'index'])->name('beranda');
Route::get('/', function() {
    return redirect()->route('login');
});

Auth::routes();

require_once __DIR__ . '/role/school.php';
require_once __DIR__ . '/role/staff.php';
require_once __DIR__ . '/role/teacher.php';
require_once __DIR__ . '/role/student.php';
require_once __DIR__ . '/role/landing.php';


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/cek-rfid', [App\Http\Controllers\HomeController::class, 'cek_rfid']);

Route::post('/send-email', [ContactUsController::class, 'sendMail'])->name('store.send.email');
Route::get('get-students', [ClassroomStudentController::class, 'show']);


