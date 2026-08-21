<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\ClassAssignment;
use App\Models\TeacherSubjectAssignment;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class TeacherManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::query();

        if ($request->filled('keyword')) {

            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {

                $q->where('teacher_code', 'like', '%' . $keyword . '%')
                    ->orWhere('full_name', 'like', '%' . $keyword . '%')
                    ->orWhere('specialization', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');

            });
        }

        $teachers = $query
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        /*
         * Lấy năm học đang hoạt động.
         * Nếu hệ thống chưa đánh dấu năm học nào đang hoạt động
         * thì sử dụng toàn bộ lịch sử phân công.
         */
        $activeYear = SchoolYear::where('is_active', 1)->first();

        foreach ($teachers as $teacher) {

            $homeroomQuery = ClassAssignment::where(
                'teacher_id',
                $teacher->id
            );

            $subjectQuery = TeacherSubjectAssignment::where(
                'teacher_id',
                $teacher->id
            );

            if ($activeYear) {

                $homeroomQuery->where(
                    'school_year_id',
                    $activeYear->id
                );

                $subjectQuery->where(
                    'school_year_id',
                    $activeYear->id
                );
            }

            $teacher->has_homeroom = $homeroomQuery->exists();

            $teacher->has_subject = $subjectQuery->exists();

            /*
             * Giáo viên chuyên được xác định bằng MÃ GIÁO VIÊN
             *
             * GVCAxxx = Tiếng Anh
             * GVCTxxx = Tin học
             */
            $code = strtoupper(trim($teacher->teacher_code ?? ''));

            $teacher->is_specialist =
                preg_match('/^GVCA[0-9]+$/', $code) ||
                preg_match('/^GVCT[0-9]+$/', $code);

            if (preg_match('/^GVCA[0-9]+$/', $code)) {

                $teacher->specialist_subject = 'Tiếng Anh';

            } elseif (preg_match('/^GVCT[0-9]+$/', $code)) {

                $teacher->specialist_subject = 'Tin học';

            } else {

                $teacher->specialist_subject = null;
            }


            /*
             * Xác định loại giáo viên để Blade tô màu
             */
            if ($teacher->is_specialist) {

                $teacher->teacher_type = 'specialist';

            } elseif (
                $teacher->has_homeroom &&
                $teacher->has_subject
            ) {

                $teacher->teacher_type = 'homeroom_subject';

            } elseif ($teacher->has_subject) {

                $teacher->teacher_type = 'subject';

            } elseif ($teacher->has_homeroom) {

                $teacher->teacher_type = 'homeroom';

            } else {

                $teacher->teacher_type = 'none';
            }
        }

        return view(
            'teachers.index',
            compact('teachers')
        );
    }
}