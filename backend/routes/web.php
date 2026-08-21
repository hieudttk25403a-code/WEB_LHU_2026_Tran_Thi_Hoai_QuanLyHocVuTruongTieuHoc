<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ClassAssignmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\StudentClassHistoryController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentHealthController;
use App\Http\Controllers\StudentParentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherSubjectAssignmentController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\TeacherScoreController;
use App\Http\Controllers\BghStudentController;
use App\Http\Controllers\BghTeacherController;
use App\Http\Controllers\BghSchoolClassController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Trang chủ
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/dashboard',
    function () {
        return view('dashboard');
    }
)->middleware([
    'auth',
    'verified'
])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/

Route::get(
    '/admin/dashboard',
    function () {
        return view('dashboard.admin');
    }
)->middleware([
    'auth',
    'role:admin'
])->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| GIÁO VIÊN - ĐIỂM DANH
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {

    Route::get(
        '/attendance',
        [TeacherAttendanceController::class, 'index']
    )->name('attendance.index');

Route::get(
    '/teacher/attendance/{class}/show',
    [TeacherAttendanceController::class, 'show']
)->name('teacher.attendance.show');
    Route::get(
        '/attendance/{class}/create',
        [TeacherAttendanceController::class, 'create']
    )->name('attendance.create');

    Route::post(
        '/attendance',
        [TeacherAttendanceController::class, 'store']
    )->name('attendance.store');

});

/*
|--------------------------------------------------------------------------
| Dashboard Giáo viên
|--------------------------------------------------------------------------
*/

Route::get(
    '/teacher/dashboard',
    function () {
        return view('dashboard.teacher');
    }
)->middleware('auth')->name('teacher.dashboard');

Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {

    Route::get('/attendance', [TeacherAttendanceController::class, 'index'])
        ->name('attendance.index');

});

/*
|--------------------------------------------------------------------------
| Dashboard Ban Giám Hiệu
|--------------------------------------------------------------------------
*/

Route::get(
    '/bgh/dashboard',
    function () {

        return view(
            'dashboard.bgh'
        );

    }
)->middleware([
    'auth',
    'role:bgh'
])->name('bgh.dashboard');


/*
|--------------------------------------------------------------------------
| BAN GIÁM HIỆU - HỌC SINH
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:bgh'
])->prefix('bgh')->name('bgh.')->group(function () {

    // HỌC SINH

    Route::get(
        '/students',
        [BghStudentController::class, 'index']
    )->name('students.index');

    Route::get(
        '/students/{student}',
        [BghStudentController::class, 'show']
    )->name('students.show');


    // GIÁO VIÊN

    Route::get(
        '/teachers',
        [BghTeacherController::class, 'index']
    )->name('teachers.index');

    Route::get(
        '/teachers/{teacher}',
        [BghTeacherController::class, 'show']
    )->name('teachers.show');


    // LỚP HỌC

    Route::get(
        '/classes',
        [BghSchoolClassController::class, 'index']
    )->name('classes.index');

    Route::get(
        '/classes/{schoolClass}',
        [BghSchoolClassController::class, 'show']
    )->name('classes.show');

});

Route::put(
    '/teacher/scores/conduct',
    [ScoreController::class, 'updateConduct']
)->name('teacher.scores.conduct.update');
/*
|--------------------------------------------------------------------------
| Thông tin cá nhân Giáo viên
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/teacher/profile',
        function () {

            $user = auth()->user();

            $teacher = $user->teacher;

            return view(
                'teachers.profile',
                compact(
                    'user',
                    'teacher'
                )
            );
        }
    )->name('teachers.profile');


    /*
    |--------------------------------------------------------------------------
    | Quản lý giảng dạy Giáo viên
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/teacher/teaching/schedule',
        [
            \App\Http\Controllers\TeacherTeachingController::class,
            'schedule'
        ]
    )->name('teacher.teaching.schedule');


    /*
    |--------------------------------------------------------------------------
    | Đổi mật khẩu Giáo viên
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/teachers/profile/password',
        [
            \App\Http\Controllers\TeacherProfileController::class,
            'updatePassword'
        ]
    )->name('teachers.password.update');

});


/*
|--------------------------------------------------------------------------
| QUẢN LÝ ĐIỂM SỐ GIÁO VIÊN
|--------------------------------------------------------------------------
|
| Giáo viên có trang quản lý điểm riêng.
| Không sử dụng trang điểm của Admin.
|
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Trang quản lý điểm của giáo viên
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/teacher/scores',
        [TeacherScoreController::class, 'index']
    )->name('teacher.scores.index');


    /*
    |--------------------------------------------------------------------------
    | Lưu điểm của giáo viên
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/teacher/scores',
        [TeacherScoreController::class, 'store']
    )->name('teacher.scores.store');


    /*
    |--------------------------------------------------------------------------
    | Lưu hạnh kiểm + kết quả học tập của GVCN
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/teacher/scores/homeroom-result',
        [TeacherScoreController::class, 'saveHomeroomResult']
    )->name('teacher.scores.homeroom-result');

});
// =========================
// ĐIỂM DANH GIÁO VIÊN
// =========================

Route::get(
    '/teacher/attendance',
    [TeacherAttendanceController::class, 'index']
)->name('teacher.attendance.index');


// Xem kết quả điểm danh
Route::get(
    '/teacher/attendance/{class}/show',
    [TeacherAttendanceController::class, 'show']
)->name('teacher.attendance.show');


// Trang nhập điểm danh
Route::get(
    '/teacher/attendance/{class}',
    [TeacherAttendanceController::class, 'create']
)->name('teacher.attendance.create');


// Lưu điểm danh
Route::post(
    '/teacher/attendance',
    [TeacherAttendanceController::class, 'store']
)->name('teacher.attendance.store');
/*
|--------------------------------------------------------------------------
| Quản lý tài khoản
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin'
])
    ->prefix('admin/accounts')
    ->name('admin.accounts.')
    ->group(function () {

        /*
        | Danh sách tài khoản
        */

        Route::get(
            '/',
            [AccountController::class, 'index']
        )->name('index');


        /*
        | Form thêm tài khoản
        */

        Route::get(
            '/create',
            [AccountController::class, 'create']
        )->name('create');


        /*
        | Lưu tài khoản mới
        */

        Route::post(
            '/',
            [AccountController::class, 'store']
        )->name('store');


        /*
        | Xem chi tiết tài khoản
        */

        Route::get(
            '/{account}',
            [AccountController::class, 'show']
        )->name('show');


        /*
        | Form chỉnh sửa / phân quyền
        */

        Route::get(
            '/{account}/edit',
            [AccountController::class, 'edit']
        )->name('edit');


        /*
        | Cập nhật tài khoản / phân quyền
        */

        Route::put(
            '/{account}',
            [AccountController::class, 'update']
        )->name('update');


        /*
        | Xóa tài khoản
        */

        Route::delete(
            '/{account}',
            [AccountController::class, 'destroy']
        )->name('destroy');

    });


/*
|--------------------------------------------------------------------------
| Học sinh
|--------------------------------------------------------------------------
*/

Route::resource(
    'students',
    StudentController::class
)->middleware('auth');


/*
|--------------------------------------------------------------------------
| Giáo viên
|--------------------------------------------------------------------------
*/

/*
| QUAN TRỌNG:
| Route assignment phải nằm trước resource teachers
*/

Route::get(
    '/teachers/assignment',
    [TeacherController::class, 'assignment']
)
    ->middleware('auth')
    ->name('teachers.assignment');


Route::resource(
    'teachers',
    TeacherController::class
)->middleware('auth');


/*
|--------------------------------------------------------------------------
| Lớp học
|--------------------------------------------------------------------------
*/

Route::prefix('classes')
    ->name('classes.')
    ->middleware('auth')
    ->group(function () {

        /*
        | Danh sách lớp
        */

        Route::get(
            '/',
            [SchoolClassController::class, 'index']
        )->name('index');


        /*
        | Form thêm lớp
        */

        Route::get(
            '/create',
            [SchoolClassController::class, 'create']
        )->name('create');


        /*
        | Lưu lớp
        */

        Route::post(
            '/',
            [SchoolClassController::class, 'store']
        )->name('store');


        /*
        | Xem lớp
        */

        Route::get(
            '/{class}',
            [SchoolClassController::class, 'show']
        )->name('show');


        /*
        | Form sửa lớp
        */

        Route::get(
            '/{class}/edit',
            [SchoolClassController::class, 'edit']
        )->name('edit');


        /*
        | Cập nhật lớp
        */

        Route::put(
            '/{class}',
            [SchoolClassController::class, 'update']
        )->name('update');


        /*
        | Xóa lớp
        */

        Route::delete(
            '/{class}',
            [SchoolClassController::class, 'destroy']
        )->name('destroy');

    });


/*
|--------------------------------------------------------------------------
| Môn học
|--------------------------------------------------------------------------
*/

Route::resource(
    'subjects',
    SubjectController::class
)->middleware('auth');


/*
|--------------------------------------------------------------------------
| ĐIỂM - ADMIN
|--------------------------------------------------------------------------
|
| Admin sử dụng:
| /scores
|
| Giáo viên sử dụng:
| /teacher/scores
|
*/

Route::resource(
    'scores',
    ScoreController::class
)->middleware('auth');


/*
|--------------------------------------------------------------------------
| Năm học
|--------------------------------------------------------------------------
*/

Route::resource(
    'school-years',
    SchoolYearController::class
)->middleware('auth');


/*
|--------------------------------------------------------------------------
| Thông báo
|--------------------------------------------------------------------------
*/

Route::resource(
    'announcements',
    AnnouncementController::class
)->middleware('auth');


/*
|--------------------------------------------------------------------------
| Phụ huynh
|--------------------------------------------------------------------------
*/

Route::get(
    '/students/{student}/parents/create',
    [StudentParentController::class, 'create']
)->middleware('auth')->name('student-parents.create');


Route::post(
    '/students/{student}/parents',
    [StudentParentController::class, 'store']
)->middleware('auth')->name('student-parents.store');


Route::get(
    '/students/{student}/parents/{parent}/edit',
    [StudentParentController::class, 'edit']
)->middleware('auth')->name('student-parents.edit');


Route::put(
    '/students/{student}/parents/{parent}',
    [StudentParentController::class, 'update']
)->middleware('auth')->name('student-parents.update');


Route::delete(
    '/students/{student}/parents/{parent}',
    [StudentParentController::class, 'destroy']
)->middleware('auth')->name('student-parents.destroy');


/*
|--------------------------------------------------------------------------
| Sức khỏe
|--------------------------------------------------------------------------
*/

Route::get(
    '/students/{student}/health/create',
    [StudentHealthController::class, 'create']
)->middleware('auth')->name('student-health.create');


Route::post(
    '/students/{student}/health',
    [StudentHealthController::class, 'store']
)->middleware('auth')->name('student-health.store');


Route::get(
    '/students/{student}/health/edit',
    [StudentHealthController::class, 'edit']
)->middleware('auth')->name('student-health.edit');


Route::put(
    '/students/{student}/health',
    [StudentHealthController::class, 'update']
)->middleware('auth')->name('student-health.update');


Route::delete(
    '/students/{student}/health',
    [StudentHealthController::class, 'destroy']
)->middleware('auth')->name('student-health.destroy');


/*
|--------------------------------------------------------------------------
| Lịch sử lớp
|--------------------------------------------------------------------------
*/

Route::resource(
    'student-class-histories',
    StudentClassHistoryController::class
)->middleware('auth');


/*
|--------------------------------------------------------------------------
| Giáo viên chủ nhiệm
|--------------------------------------------------------------------------
*/

Route::post(
    '/class-assignments/{classAssignment}/end',
    [ClassAssignmentController::class, 'end']
)
    ->middleware('auth')
    ->name('class-assignments.end');


Route::resource(
    'class-assignments',
    ClassAssignmentController::class
)->middleware('auth');


/*
|--------------------------------------------------------------------------
| Giáo viên bộ môn
|--------------------------------------------------------------------------
*/

/*
| teacher-lookup PHẢI nằm trước resource
*/

Route::get(
    '/teacher-subject-assignments/teacher-lookup',
    [
        TeacherSubjectAssignmentController::class,
        'teacherLookup'
    ]
)
    ->middleware('auth')
    ->name('teacher-subject-assignments.teacher-lookup');


Route::resource(
    'teacher-subject-assignments',
    TeacherSubjectAssignmentController::class
)->middleware('auth');


/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');


    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');


    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Teacher Assignments
|--------------------------------------------------------------------------
*/

Route::get(
    '/teacher-assignments',
    function () {
        return view('teacher_assignments.index');
    }
)
    ->middleware('auth')
    ->name('teacher-assignments.index');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';