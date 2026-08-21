<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    /**
     * Danh sách lớp học
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Danh sách năm học
        |--------------------------------------------------------------------------
        */
        $schoolYears = SchoolYear::orderByDesc('start_date')->get();

        /*
        |--------------------------------------------------------------------------
        | Xác định năm học đang xem
        |--------------------------------------------------------------------------
        */
        $schoolYearId = $request->school_year_id;

        // Nếu người dùng chưa chọn năm học
        if (!$schoolYearId) {

            // Ưu tiên năm học đang hoạt động
            $activeYear = SchoolYear::where('is_active', 1)->first();

            if ($activeYear) {
                $schoolYearId = $activeYear->id;
            } else {
                // Nếu chưa có năm học đang hoạt động
                // thì lấy năm học mới nhất
                $latestYear = SchoolYear::orderByDesc('start_date')->first();

                if ($latestYear) {
                    $schoolYearId = $latestYear->id;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Lấy đối tượng năm học đang xem
        |--------------------------------------------------------------------------
        */
        $schoolYear = $schoolYearId
            ? SchoolYear::find($schoolYearId)
            : null;

        /*
        |--------------------------------------------------------------------------
        | Query lớp học
        |--------------------------------------------------------------------------
        */
        $query = SchoolClass::query();

        /*
        |--------------------------------------------------------------------------
        | Tìm kiếm
        |--------------------------------------------------------------------------
        */
        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'class_name',
                    'like',
                    '%' . $keyword . '%'
                )

                ->orWhere(
                    'grade',
                    'like',
                    '%' . $keyword . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Lọc theo khối
        |--------------------------------------------------------------------------
        */
        if ($request->filled('grade')) {

            $query->where(
                'grade',
                $request->grade
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Danh sách lớp
        |--------------------------------------------------------------------------
        |
        | Ở đây KHÔNG lọc class theo school_year_id vì bảng
        | school_classes hiện tại của bạn không có school_year_id.
        |
        | Năm học được dùng để xác định:
        | - GVCN hiện tại
        | - dữ liệu học sinh
        | - lịch sử phân công
        |
        */
        $classes = $query
            ->orderBy('grade')
            ->orderBy('class_name')
            ->paginate(18)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Load dữ liệu liên quan
        |--------------------------------------------------------------------------
        */
        $classes->load([
            'classAssignments.teacher',
            'classAssignments.schoolYear',
            'students',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Khối lớp
        |--------------------------------------------------------------------------
        |
        | Trường tiểu học có 5 khối:
        | 1, 2, 3, 4, 5
        |
        */
        $grades = [1, 2, 3, 4, 5];

        /*
        |--------------------------------------------------------------------------
        | Xác định GVCN hiện tại của từng lớp
        |--------------------------------------------------------------------------
        |
        | Chỉ lấy phân công thuộc năm học đang xem.
        |
        */
        foreach ($classes as $class) {

            $currentAssignment = null;

            if ($schoolYearId) {

                $currentAssignment = $class
                    ->classAssignments
                    ->where(
                        'school_year_id',
                        $schoolYearId
                    )
                    ->sortByDesc('id')
                    ->first();
            }

            /*
            |--------------------------------------------------------------------------
            | Giáo viên chủ nhiệm hiện tại
            |--------------------------------------------------------------------------
            */
            $class->current_homeroom_teacher =
                $currentAssignment
                    ? $currentAssignment->teacher
                    : null;

            /*
            |--------------------------------------------------------------------------
            | Phân công GVCN hiện tại
            |--------------------------------------------------------------------------
            */
            $class->current_assignment =
                $currentAssignment;

            /*
            |--------------------------------------------------------------------------
            | Lịch sử GVCN
            |--------------------------------------------------------------------------
            */
            $class->homeroom_history =
                $class->classAssignments
                    ->filter(function ($assignment) use ($schoolYearId) {

                        return $assignment->school_year_id
                            == $schoolYearId;
                    })
                    ->sortByDesc('id')
                    ->values();

            /*
            |--------------------------------------------------------------------------
            | Sĩ số
            |--------------------------------------------------------------------------
            |
            | Hiện tại bảng students có class_id.
            |
            | Nếu student đang thuộc lớp này thì tính vào sĩ số,
            | loại những học sinh đã chuyển trường / đuổi học.
            |
            */
            if ($schoolYearId) {

                $class->current_student_count =
                    $class->students
                        ->filter(function ($student) {

                            return !in_array(
                                mb_strtolower(
                                    trim($student->status ?? '')
                                ),
                                [
                                    'chuyển trường',
                                    'đuổi học',
                                ]
                            );
                        })
                        ->count();

            } else {

                $class->current_student_count =
                    $class->students->count();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Trả dữ liệu sang View
        |--------------------------------------------------------------------------
        */
        return view(
            'classes.index',
            compact(
                'classes',
                'schoolYears',
                'schoolYearId',
                'schoolYear',
                'grades'
            )
        );
    }

    /**
     * Form thêm lớp
     */
    public function create()
    {
        return view(
            'classes.create'
        );
    }

    /**
     * Lưu lớp mới
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'class_name' => 'required|string|max:255',

                'grade' => [
                    'required',
                    'string',
                    'in:1,2,3,4,5',
                ],

                'homeroom_teacher' =>
                    'nullable|string|max:255',

                'student_count' =>
                    'nullable|integer|min:0',

                'status' =>
                    'required|string|max:255',
            ],
            [
                'class_name.required' =>
                    'Vui lòng nhập tên lớp.',

                'grade.required' =>
                    'Vui lòng chọn khối.',

                'grade.in' =>
                    'Khối phải là 1, 2, 3, 4 hoặc 5.',

                'student_count.integer' =>
                    'Sĩ số phải là số.',

                'student_count.min' =>
                    'Sĩ số không được nhỏ hơn 0.',

                'status.required' =>
                    'Vui lòng chọn trạng thái.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Không cho trùng tên lớp
        |--------------------------------------------------------------------------
        */
        $exists = SchoolClass::where(
            'class_name',
            $request->class_name
        )->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'class_name' =>
                        'Tên lớp này đã tồn tại.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Tạo lớp
        |--------------------------------------------------------------------------
        */
        SchoolClass::create([
            'class_name' =>
                $request->class_name,

            'grade' =>
                $request->grade,

            'homeroom_teacher' =>
                $request->homeroom_teacher,

            'student_count' =>
                $request->student_count ?? 0,

            'status' =>
                $request->status,
        ]);

        return redirect()
            ->route('classes.index')
            ->with(
                'success',
                'Thêm lớp học thành công!'
            );
    }

    /**
     * Xem chi tiết lớp
     */
    public function show(
        SchoolClass $class
    ) {
        /*
        |--------------------------------------------------------------------------
        | Load dữ liệu
        |--------------------------------------------------------------------------
        */
        $class->load([
            'students',

            'classAssignments.teacher',

            'classAssignments.schoolYear',

            'teacherSubjectAssignments.teacher',

            'teacherSubjectAssignments.subject',

            'teacherSubjectAssignments.schoolYear',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Xác định năm học đang hoạt động
        |--------------------------------------------------------------------------
        */
        $activeYear =
            SchoolYear::where(
                'is_active',
                1
            )->first();

        /*
        |--------------------------------------------------------------------------
        | GVCN hiện tại
        |--------------------------------------------------------------------------
        */
        $currentAssignment = null;

        if ($activeYear) {

            $currentAssignment =
                $class->classAssignments
                    ->where(
                        'school_year_id',
                        $activeYear->id
                    )
                    ->sortByDesc('id')
                    ->first();
        }

        $currentTeacher =
            $currentAssignment
                ? $currentAssignment->teacher
                : null;

        /*
        |--------------------------------------------------------------------------
        | Lịch sử GVCN
        |--------------------------------------------------------------------------
        */
        $homeroomHistory =
            $class->classAssignments
                ->sortByDesc('id')
                ->values();

        /*
        |--------------------------------------------------------------------------
        | Sĩ số
        |--------------------------------------------------------------------------
        */
        $studentCount =
            $class->students
                ->filter(function ($student) {

                    return !in_array(
                        mb_strtolower(
                            trim($student->status ?? '')
                        ),
                        [
                            'chuyển trường',
                            'đuổi học',
                        ]
                    );
                })
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Trả view
        |--------------------------------------------------------------------------
        */
        return view(
            'classes.show',
            compact(
                'class',
                'activeYear',
                'currentAssignment',
                'currentTeacher',
                'homeroomHistory',
                'studentCount'
            )
        );
    }

    /**
     * Form chỉnh sửa lớp
     */
    public function edit(
        SchoolClass $class
    ) {
        return view(
            'classes.edit',
            compact('class')
        );
    }

    /**
     * Cập nhật lớp
     */
    public function update(
        Request $request,
        SchoolClass $class
    ) {
        $request->validate(
            [
                'class_name' =>
                    'required|string|max:255',

                'grade' => [
                    'required',
                    'string',
                    'in:1,2,3,4,5',
                ],

                'homeroom_teacher' =>
                    'nullable|string|max:255',

                'student_count' =>
                    'nullable|integer|min:0',

                'status' =>
                    'required|string|max:255',
            ],
            [
                'class_name.required' =>
                    'Vui lòng nhập tên lớp.',

                'grade.required' =>
                    'Vui lòng chọn khối.',

                'grade.in' =>
                    'Khối phải là 1, 2, 3, 4 hoặc 5.',

                'student_count.integer' =>
                    'Sĩ số phải là số.',

                'student_count.min' =>
                    'Sĩ số không được nhỏ hơn 0.',

                'status.required' =>
                    'Vui lòng chọn trạng thái.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Kiểm tra trùng tên lớp
        |--------------------------------------------------------------------------
        */
        $exists = SchoolClass::where(
            'class_name',
            $request->class_name
        )
            ->where(
                'id',
                '!=',
                $class->id
            )
            ->exists();

        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'class_name' =>
                        'Tên lớp này đã tồn tại.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Cập nhật
        |--------------------------------------------------------------------------
        */
        $class->update([
            'class_name' =>
                $request->class_name,

            'grade' =>
                $request->grade,

            'homeroom_teacher' =>
                $request->homeroom_teacher,

            'student_count' =>
                $request->student_count ?? 0,

            'status' =>
                $request->status,
        ]);

        return redirect()
            ->route(
                'classes.show',
                $class
            )
            ->with(
                'success',
                'Cập nhật lớp học thành công!'
            );
    }

    /**
     * Xóa lớp
     */
    public function destroy(
        SchoolClass $class
    ) {
        /*
        |--------------------------------------------------------------------------
        | Không cho xóa nếu còn học sinh
        |--------------------------------------------------------------------------
        */
        if ($class->students()->exists()) {

            return back()
                ->withErrors([
                    'class' =>
                        'Không thể xóa lớp vì lớp vẫn còn học sinh.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Không cho xóa nếu còn lịch sử GVCN
        |--------------------------------------------------------------------------
        */
        if ($class->classAssignments()->exists()) {

            return back()
                ->withErrors([
                    'class' =>
                        'Không thể xóa lớp vì lớp vẫn còn lịch sử phân công giáo viên chủ nhiệm.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Không cho xóa nếu còn phân công giáo viên bộ môn
        |--------------------------------------------------------------------------
        */
        if (
            $class
                ->teacherSubjectAssignments()
                ->exists()
        ) {

            return back()
                ->withErrors([
                    'class' =>
                        'Không thể xóa lớp vì lớp vẫn còn phân công giáo viên bộ môn.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Xóa lớp
        |--------------------------------------------------------------------------
        */
        $class->delete();

        return redirect()
            ->route('classes.index')
            ->with(
                'success',
                'Xóa lớp học thành công!'
            );
    }
}