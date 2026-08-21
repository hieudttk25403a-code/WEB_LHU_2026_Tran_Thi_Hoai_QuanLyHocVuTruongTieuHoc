<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassAssignment;
use App\Models\SchoolClass;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherSubjectAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherAttendanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Lấy giáo viên đang đăng nhập
    |--------------------------------------------------------------------------
    */
    private function getTeacher()
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Bạn chưa đăng nhập.');
        }

        if (!$user->teacher_id) {
            abort(
                403,
                'Tài khoản giáo viên chưa được liên kết với hồ sơ giáo viên.'
            );
        }

        $teacher = Teacher::find($user->teacher_id);

        if (!$teacher) {
            abort(
                403,
                'Không tìm thấy hồ sơ giáo viên.'
            );
        }

        return $teacher;
    }

    /*
    |--------------------------------------------------------------------------
    | Lấy năm học đang hoạt động
    |--------------------------------------------------------------------------
    */
    private function getActiveSchoolYear()
    {
        return SchoolYear::where('is_active', 1)
            ->orderByDesc('id')
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Kiểm tra giáo viên có được phân công lớp hay không
    |
    | Giáo viên có thể:
    | - Là GVCN
    | - Là giáo viên bộ môn của lớp
    |--------------------------------------------------------------------------
    */
    private function teacherCanAccessClass(
        Teacher $teacher,
        $classId,
        $schoolYearId
    ) {
        $isHomeroom = ClassAssignment::where(
            'teacher_id',
            $teacher->id
        )
            ->where('class_id', $classId)
            ->where('school_year_id', $schoolYearId)
            ->exists();

        if ($isHomeroom) {
            return true;
        }

        $isSubjectTeacher = TeacherSubjectAssignment::where(
            'teacher_id',
            $teacher->id
        )
            ->where('class_id', $classId)
            ->where('school_year_id', $schoolYearId)
            ->exists();

        return $isSubjectTeacher;
    }

    /*
    |--------------------------------------------------------------------------
    | Danh sách lớp của giáo viên
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $teacher = $this->getTeacher();

        $schoolYears = SchoolYear::orderByDesc('id')
            ->get();

        $schoolYearId = $request->school_year_id;

        if (!$schoolYearId) {
            $activeYear = $this->getActiveSchoolYear();

            if ($activeYear) {
                $schoolYearId = $activeYear->id;
            }
        }

        $classes = collect();

        if ($schoolYearId) {

            /*
            |--------------------------------------------------------------------------
            | Lớp giáo viên chủ nhiệm
            |--------------------------------------------------------------------------
            */
            $homeroomClassIds = ClassAssignment::where(
                'teacher_id',
                $teacher->id
            )
                ->where(
                    'school_year_id',
                    $schoolYearId
                )
                ->pluck('class_id');

            /*
            |--------------------------------------------------------------------------
            | Lớp giáo viên bộ môn
            |--------------------------------------------------------------------------
            */
            $subjectClassIds = TeacherSubjectAssignment::where(
                'teacher_id',
                $teacher->id
            )
                ->where(
                    'school_year_id',
                    $schoolYearId
                )
                ->pluck('class_id');

            $classIds = $homeroomClassIds
                ->merge($subjectClassIds)
                ->unique()
                ->values();

            $classes = SchoolClass::whereIn(
                'id',
                $classIds
            )
                ->orderBy('grade')
                ->orderBy('class_name')
                ->get();
        }

        $schoolYear = null;

        if ($schoolYearId) {
            $schoolYear = SchoolYear::find($schoolYearId);
        }

        return view(
            'teacher.attendance.index',
            compact(
                'teacher',
                'classes',
                'schoolYears',
                'schoolYear'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Trang điểm danh một lớp
    |--------------------------------------------------------------------------
    */
    public function create(
        Request $request,
        SchoolClass $class
    ) {
        $teacher = $this->getTeacher();

        $schoolYearId = $request->school_year_id;

        if (!$schoolYearId) {

            $activeYear = $this->getActiveSchoolYear();

            if (!$activeYear) {
                return redirect()
                    ->route('teacher.attendance.index')
                    ->with(
                        'error',
                        'Chưa có năm học đang hoạt động.'
                    );
            }

            $schoolYearId = $activeYear->id;
        }

        $schoolYear = SchoolYear::findOrFail(
            $schoolYearId
        );

        /*
        |--------------------------------------------------------------------------
        | Kiểm tra quyền truy cập lớp
        |--------------------------------------------------------------------------
        */
        if (!$this->teacherCanAccessClass(
            $teacher,
            $class->id,
            $schoolYear->id
        )) {

            abort(
                403,
                'Bạn không được phân công với lớp này.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Lấy học sinh của lớp
        |--------------------------------------------------------------------------
        |
        | students.class_id là lớp hiện tại.
        |
        */
        $students = Student::where(
            'class_id',
            $class->id
        )
            ->whereNotIn(
                'status',
                [
                    'chuyển trường',
                    'đuổi học'
                ]
            )
            ->orderBy('full_name')
            ->get();

        $date = $request->date;

        if (!$date) {
            $date = now()->format('Y-m-d');
        }

        /*
        |--------------------------------------------------------------------------
        | Lấy dữ liệu điểm danh đã có
        |--------------------------------------------------------------------------
        */
        $attendances = Attendance::where(
            'class_id',
            $class->id
        )
            ->where(
                'teacher_id',
                $teacher->id
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->whereDate(
                'attendance_date',
                $date
            )
            ->get()
            ->keyBy('student_id');

        return view(
            'teacher.attendance.create',
            compact(
                'teacher',
                'class',
                'schoolYear',
                'students',
                'attendances',
                'date'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Lưu điểm danh
    |--------------------------------------------------------------------------
    */
/**
 * Hiển thị kết quả điểm danh của một ngày.
 */
public function show(
    Request $request,
    SchoolClass $class
) {
    $teacher = $this->getTeacher();

    $schoolYearId = $request->school_year_id;

    if (!$schoolYearId) {
        $activeYear = $this->getActiveSchoolYear();

        if (!$activeYear) {
            return redirect()
                ->route('teacher.attendance.index')
                ->with(
                    'error',
                    'Chưa có năm học đang hoạt động.'
                );
        }

        $schoolYearId = $activeYear->id;
    }

    $schoolYear = SchoolYear::findOrFail(
        $schoolYearId
    );

    // Kiểm tra giáo viên có quyền với lớp
    if (!$this->teacherCanAccessClass(
        $teacher,
        $class->id,
        $schoolYear->id
    )) {
        abort(
            403,
            'Bạn không được phân công với lớp này.'
        );
    }

    // Ngày cần xem
    $date = $request->date;

    if (!$date) {
        $date = now()->format('Y-m-d');
    }

    // Lấy toàn bộ học sinh của lớp
    $students = Student::where(
        'class_id',
        $class->id
    )
        ->whereNotIn(
            'status',
            [
                'chuyển trường',
                'đuổi học'
            ]
        )
        ->orderBy('full_name')
        ->get();

    // Lấy điểm danh của ngày đó
    $attendances = Attendance::with('student')
        ->where(
            'class_id',
            $class->id
        )
        ->where(
            'teacher_id',
            $teacher->id
        )
        ->where(
            'school_year_id',
            $schoolYear->id
        )
        ->whereDate(
            'attendance_date',
            $date
        )
        ->get()
        ->keyBy('student_id');

    return view(
        'teacher.attendance.show',
        compact(
            'teacher',
            'class',
            'schoolYear',
            'students',
            'attendances',
            'date'
        )
    );
}

    public function store(Request $request)
    {
        $teacher = $this->getTeacher();

        $request->validate([
            'class_id' => [
                'required',
                'exists:school_classes,id'
            ],

            'school_year_id' => [
                'required',
                'exists:school_years,id'
            ],

            'attendance_date' => [
                'required',
                'date'
            ],

            'attendance' => [
                'required',
                'array'
            ],

            'attendance.*.student_id' => [
                'required',
                'exists:students,id'
            ],

            'attendance.*.status' => [
                'required',
                'in:present,absent,late,excused'
            ],

            'attendance.*.note' => [
                'nullable',
                'string',
                'max:255'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Kiểm tra giáo viên có quyền điểm danh lớp
        |--------------------------------------------------------------------------
        */
        if (!$this->teacherCanAccessClass(
            $teacher,
            $request->class_id,
            $request->school_year_id
        )) {

            abort(
                403,
                'Bạn không được phân công với lớp này.'
            );
        }

        DB::transaction(function () use (
            $request,
            $teacher
        ) {

            foreach (
                $request->attendance as $item
            ) {

                $student = Student::find(
                    $item['student_id']
                );

                if (!$student) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Đảm bảo học sinh thực sự thuộc lớp
                |--------------------------------------------------------------------------
                */
                if (
                    (int) $student->class_id
                    !==
                    (int) $request->class_id
                ) {
                    continue;
                }

                Attendance::updateOrCreate(
                    [
                        'student_id' => $student->id,

                        'attendance_date' =>
                            $request->attendance_date,
                    ],
                    [
                        'class_id' =>
                            $request->class_id,

                        'teacher_id' =>
                            $teacher->id,

                        'school_year_id' =>
                            $request->school_year_id,

                        'status' =>
                            $item['status'],

                        'note' =>
                            $item['note'] ?? null,
                    ]
                );
            }
        });

return redirect()
    ->route(
        'teacher.attendance.show',
        [
            'class' => $request->class_id,
            'school_year_id' => $request->school_year_id,
            'date' => $request->attendance_date,
        ]
    )
    ->with(
        'success',
        'Lưu điểm danh thành công!'
    );
    }
}