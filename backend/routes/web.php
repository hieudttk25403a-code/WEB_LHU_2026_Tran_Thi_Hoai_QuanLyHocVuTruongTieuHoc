<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\StudentParentController;
use App\Http\Controllers\StudentHealthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for the application.
|
*/

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard mặc định của Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard Admin
Route::get('/admin/dashboard', function () {
    return view('dashboard.admin');
})->middleware(['auth'])->name('admin.dashboard');

// =========================
// Quản lý học sinh
// =========================
Route::resource('students', StudentController::class)
    ->middleware('auth');

// =========================
// Quản lý giáo viên
// =========================
Route::resource('teachers', TeacherController::class)
    ->middleware('auth');

//Quản lý lớp học
Route::resource('classes', SchoolClassController::class)
    ->middleware('auth');

// Quản lý môn học
Route::resource('subjects', SubjectController::class)
    ->middleware('auth');

// Quản lý điểm
Route::resource('scores', ScoreController::class)
    ->middleware('auth');

//Quản lý năm học 
Route::resource('school-years', SchoolYearController::class)
    ->middleware('auth');

// Quản lý thông báo 
Route::resource('announcements', AnnouncementController::class)
    ->middleware('auth');

// Quản lý thời khóa biểu
Route::resource('timetables', TimetableController::class)
    ->middleware('auth');

//Quản lý phụ huynh
Route::post(
    '/students/{student}/parents',
    [StudentParentController::class, 'store']
)->name('students.parents.store');

Route::put(
    '/students/{student}/parents/{parent}',
    [StudentParentController::class, 'update']
)->name('students.parents.update');

Route::delete(
    '/students/{student}/parents/{parent}',
    [StudentParentController::class, 'destroy']
)->name('students.parents.destroy');

//Quản lý sức khỏe
Route::post(
    '/students/{student}/health',
    [StudentHealthController::class, 'store']
)->name('students.health.store');

Route::delete(
    '/student-health/{studentHealth}',
    [StudentHealthController::class, 'destroy']
)->name('students.health.destroy');
// =========================
// Quản lý tài khoản
// =========================
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Route xác thực
require __DIR__ . '/auth.php';