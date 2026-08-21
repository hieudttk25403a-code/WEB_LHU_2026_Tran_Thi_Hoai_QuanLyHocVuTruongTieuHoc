<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\SchoolYear;
use App\Models\TeacherSubjectAssignment;
use Illuminate\Http\Request;

class TeacherSubjectAssignmentController extends Controller
{
    /**
     * Danh sách phân công giáo viên bộ môn.
     */
    public function index(Request $request)
    {
        $query = TeacherSubjectAssignment::with([
            'teacher',
            'subject',
            'schoolClass',
            'schoolYear',
        ]);

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->whereHas('teacher', function ($q) use ($keyword) {

                $q->where(
                    'teacher_code',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'full_name',
                    'like',
                    "%{$keyword}%"
                );

            });
        }

        if ($request->filled('subject_id')) {

            $query->where(
                'subject_id',
                $request->subject_id
            );
        }

        if ($request->filled('class_id')) {

            $query->where(
                'class_id',
                $request->class_id
            );
        }

        if ($request->filled('school_year_id')) {

            $query->where(
                'school_year_id',
                $request->school_year_id
            );
        }

        $assignments = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $subjects = Subject::orderBy('subject_name')->get();

        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();

        $schoolYears = SchoolYear::orderByDesc('id')->get();

        return view(
            'teacher_subject_assignments.index',
            compact(
                'assignments',
                'subjects',
                'classes',
                'schoolYears'
            )
        );
    }


    /**
     * Form phân công giáo viên bộ môn.
     */
    public function create()
    {
        $teachers = Teacher::orderBy('teacher_code')->get();

        $subjects = Subject::orderBy('subject_name')->get();

        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();

        $schoolYears = SchoolYear::orderByDesc('id')->get();

        return view(
            'teacher_subject_assignments.subject_create',
            compact(
                'teachers',
                'subjects',
                'classes',
                'schoolYears'
            )
        );
    }


    /**
     * Lưu phân công.
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => [
                'required',
                'exists:teachers,id',
            ],

            'subject_id' => [
                'required',
                'exists:subjects,id',
            ],

            'class_id' => [
                'required',
                'exists:school_classes,id',
            ],

            'school_year_id' => [
                'required',
                'exists:school_years,id',
            ],

            'day_of_week' => [
                'required',
                'integer',
                'between:1,7',
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [

            'teacher_id.required' =>
                'Vui lòng chọn giáo viên.',

            'teacher_id.exists' =>
                'Giáo viên không tồn tại.',

            'subject_id.required' =>
                'Vui lòng chọn môn học.',

            'subject_id.exists' =>
                'Môn học không tồn tại.',

            'class_id.required' =>
                'Vui lòng chọn lớp.',

            'class_id.exists' =>
                'Lớp không tồn tại.',

            'school_year_id.required' =>
                'Vui lòng chọn năm học.',

            'school_year_id.exists' =>
                'Năm học không tồn tại.',

            'day_of_week.required' =>
                'Vui lòng chọn thứ.',

            'day_of_week.integer' =>
                'Thứ không hợp lệ.',

            'day_of_week.between' =>
                'Thứ phải từ Thứ 2 đến Chủ nhật.',
        ]);


        $teacher = Teacher::findOrFail(
            $request->teacher_id
        );

        $subject = Subject::findOrFail(
            $request->subject_id
        );


        /*
        |--------------------------------------------------------------------------
        | Giáo viên chuyên Anh / Tin
        |--------------------------------------------------------------------------
        |
        | GVCA -> Tiếng Anh
        | GVTH / GVCT -> Tin học
        |
        */

        $code = strtoupper(
            trim($teacher->teacher_code)
        );

        $specialSubjectId = null;


        if (str_starts_with($code, 'GVCA')) {

            $english = Subject::where(
                'subject_name',
                'Tiếng Anh'
            )->first();

            if ($english) {

                $specialSubjectId = $english->id;
            }
        }

        elseif (
            str_starts_with($code, 'GVTH') ||
            str_starts_with($code, 'GVCT')
        ) {

            $computer = Subject::where(
                'subject_name',
                'Tin học'
            )->first();

            if ($computer) {

                $specialSubjectId = $computer->id;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Giáo viên chuyên chỉ được dạy môn chuyên
        |--------------------------------------------------------------------------
        */

        if ($specialSubjectId !== null) {

            if ($request->subject_id != $specialSubjectId) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'subject_id' =>
                            'Giáo viên chuyên chỉ được phân công môn chuyên của giáo viên.'
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Không cho phân công trùng
        |--------------------------------------------------------------------------
        |
        | Một giáo viên có thể dạy cùng:
        | - môn
        | - lớp
        | - năm học
        |
        | nhưng được phép ở những thứ khác nhau.
        |
        */

        $exists = TeacherSubjectAssignment::where(
            'teacher_id',
            $request->teacher_id
        )
            ->where(
                'subject_id',
                $request->subject_id
            )
            ->where(
                'class_id',
                $request->class_id
            )
            ->where(
                'school_year_id',
                $request->school_year_id
            )
            ->where(
                'day_of_week',
                $request->day_of_week
            )
            ->exists();


        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'teacher_id' =>
                        'Giáo viên này đã được phân công môn này cho lớp này vào thứ đã chọn trong năm học này.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Lưu phân công
        |--------------------------------------------------------------------------
        |
        | Không sử dụng tiết học vì hệ thống đã bỏ chức năng Thời khóa biểu.
        |
        */

        TeacherSubjectAssignment::create([

            'teacher_id' =>
                $request->teacher_id,

            'subject_id' =>
                $request->subject_id,

            'class_id' =>
                $request->class_id,

            'school_year_id' =>
                $request->school_year_id,

            'day_of_week' =>
                $request->day_of_week,

            'period' =>
                null,

            'start_date' =>
                null,

            'end_date' =>
                null,

            'note' =>
                $request->note,
        ]);


        return redirect()
            ->route(
                'teacher-subject-assignments.index'
            )
            ->with(
                'success',
                'Phân công giáo viên bộ môn thành công!'
            );
    }


    /**
     * Chi tiết.
     */
    public function show(
        TeacherSubjectAssignment $teacherSubjectAssignment
    ) {
        $teacherSubjectAssignment->load([
            'teacher',
            'subject',
            'schoolClass',
            'schoolYear',
        ]);

        return view(
            'teacher_subject_assignments.show',
            compact(
                'teacherSubjectAssignment'
            )
        );
    }


    /**
     * Form sửa.
     */
    public function edit(
        TeacherSubjectAssignment $teacherSubjectAssignment
    ) {
        $teachers = Teacher::orderBy(
            'teacher_code'
        )->get();

        $subjects = Subject::orderBy(
            'subject_name'
        )->get();

        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();

        $schoolYears = SchoolYear::orderByDesc(
            'id'
        )->get();


        return view(
            'teacher_subject_assignments.edit',
            compact(
                'teacherSubjectAssignment',
                'teachers',
                'subjects',
                'classes',
                'schoolYears'
            )
        );
    }


    /**
     * Cập nhật.
     */
    public function update(
        Request $request,
        TeacherSubjectAssignment $teacherSubjectAssignment
    ) {
        $request->validate([
            'teacher_id' =>
                'required|exists:teachers,id',

            'subject_id' =>
                'required|exists:subjects,id',

            'class_id' =>
                'required|exists:school_classes,id',

            'school_year_id' =>
                'required|exists:school_years,id',

            'day_of_week' =>
                'required|integer|between:1,7',

            'note' =>
                'nullable|string|max:1000',
        ], [

            'teacher_id.required' =>
                'Vui lòng chọn giáo viên.',

            'teacher_id.exists' =>
                'Giáo viên không tồn tại.',

            'subject_id.required' =>
                'Vui lòng chọn môn học.',

            'subject_id.exists' =>
                'Môn học không tồn tại.',

            'class_id.required' =>
                'Vui lòng chọn lớp.',

            'class_id.exists' =>
                'Lớp không tồn tại.',

            'school_year_id.required' =>
                'Vui lòng chọn năm học.',

            'school_year_id.exists' =>
                'Năm học không tồn tại.',

            'day_of_week.required' =>
                'Vui lòng chọn thứ.',

            'day_of_week.integer' =>
                'Thứ không hợp lệ.',

            'day_of_week.between' =>
                'Thứ phải từ Thứ 2 đến Chủ nhật.',
        ]);


        $teacher = Teacher::findOrFail(
            $request->teacher_id
        );


        $code = strtoupper(
            trim($teacher->teacher_code)
        );


        /*
        |--------------------------------------------------------------------------
        | Giáo viên chuyên Anh
        |--------------------------------------------------------------------------
        */

        if (str_starts_with($code, 'GVCA')) {

            $english = Subject::where(
                'subject_name',
                'Tiếng Anh'
            )->first();

            if (
                $english &&
                $request->subject_id != $english->id
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'subject_id' =>
                            'Giáo viên chuyên Anh chỉ được dạy Tiếng Anh.'
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Giáo viên chuyên Tin
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with($code, 'GVTH') ||
            str_starts_with($code, 'GVCT')
        ) {

            $computer = Subject::where(
                'subject_name',
                'Tin học'
            )->first();

            if (
                $computer &&
                $request->subject_id != $computer->id
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'subject_id' =>
                            'Giáo viên chuyên Tin chỉ được dạy Tin học.'
                    ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Kiểm tra trùng khi cập nhật
        |--------------------------------------------------------------------------
        */

        $exists = TeacherSubjectAssignment::where(
            'teacher_id',
            $request->teacher_id
        )
            ->where(
                'subject_id',
                $request->subject_id
            )
            ->where(
                'class_id',
                $request->class_id
            )
            ->where(
                'school_year_id',
                $request->school_year_id
            )
            ->where(
                'day_of_week',
                $request->day_of_week
            )
            ->where(
                'id',
                '!=',
                $teacherSubjectAssignment->id
            )
            ->exists();


        if ($exists) {

            return back()
                ->withInput()
                ->withErrors([
                    'teacher_id' =>
                        'Phân công này đã tồn tại vào thứ đã chọn.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Cập nhật
        |--------------------------------------------------------------------------
        */

        $teacherSubjectAssignment->update([

            'teacher_id' =>
                $request->teacher_id,

            'subject_id' =>
                $request->subject_id,

            'class_id' =>
                $request->class_id,

            'school_year_id' =>
                $request->school_year_id,

            'day_of_week' =>
                $request->day_of_week,

            /*
            | Không sử dụng tiết học.
            */

            'period' =>
                null,

            /*
            | Không sử dụng ngày bắt đầu/kết thúc.
            */

            'start_date' =>
                null,

            'end_date' =>
                null,

            'note' =>
                $request->note,
        ]);


        return redirect()
            ->route(
                'teacher-subject-assignments.index'
            )
            ->with(
                'success',
                'Cập nhật phân công thành công!'
            );
    }


    /**
     * Xóa.
     */
    public function destroy(
        TeacherSubjectAssignment $teacherSubjectAssignment
    ) {
        $teacherSubjectAssignment->delete();

        return redirect()
            ->route(
                'teacher-subject-assignments.index'
            )
            ->with(
                'success',
                'Xóa phân công thành công!'
            );
    }
}