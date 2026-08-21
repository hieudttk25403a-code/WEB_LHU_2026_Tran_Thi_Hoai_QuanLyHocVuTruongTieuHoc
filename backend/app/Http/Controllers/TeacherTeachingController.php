<?php

namespace App\Http\Controllers;

use App\Models\TeacherSubjectAssignment;

class TeacherTeachingController extends Controller
{
    /**
     * Hiển thị lịch giảng dạy của giáo viên đang đăng nhập.
     */
    public function schedule()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Lấy hồ sơ giáo viên
        |--------------------------------------------------------------------------
        */

        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()
                ->route('teacher.dashboard')
                ->with(
                    'error',
                    'Tài khoản chưa được liên kết với hồ sơ giáo viên.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Lấy các phân công giảng dạy đang còn hiệu lực
        |--------------------------------------------------------------------------
        |
        | Chỉ lấy:
        | - Đúng giáo viên đang đăng nhập
        | - Chưa hết hạn
        | - Đã bắt đầu hoặc không có ngày bắt đầu
        |
        */

        $assignments = TeacherSubjectAssignment::with([
            'subject',
            'schoolClass',
            'schoolYear',
        ])
            ->where('teacher_id', $teacher->id)
            ->where(function ($query) {

                $query
                    ->whereNull('start_date')
                    ->orWhereDate(
                        'start_date',
                        '<=',
                        now()->toDateString()
                    );

            })
            ->where(function ($query) {

                $query
                    ->whereNull('end_date')
                    ->orWhereDate(
                        'end_date',
                        '>=',
                        now()->toDateString()
                    );

            })
            ->orderBy('day_of_week')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Trả về giao diện
        |--------------------------------------------------------------------------
        */

        return view(
            'teachers.teaching.schedule',
            compact(
                'teacher',
                'assignments'
            )
        );
    }
}