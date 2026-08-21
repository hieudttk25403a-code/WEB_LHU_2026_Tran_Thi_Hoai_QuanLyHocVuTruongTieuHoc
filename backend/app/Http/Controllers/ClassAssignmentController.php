<?php

namespace App\Http\Controllers;

use App\Models\ClassAssignment;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ClassAssignmentController extends Controller
{
    /**
     * Danh sách phân công giáo viên chủ nhiệm.
     */
    public function index(Request $request)
    {
        $query = ClassAssignment::with([
            'teacher',
            'schoolClass',
            'schoolYear',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Tìm giáo viên
        |--------------------------------------------------------------------------
        */
        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->whereHas('teacher', function ($q) use ($keyword) {

                $q->where('teacher_code', 'like', "%{$keyword}%")
                    ->orWhere('full_name', 'like', "%{$keyword}%");

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Lọc lớp
        |--------------------------------------------------------------------------
        */
        if ($request->filled('class_id')) {

            $query->where(
                'class_id',
                $request->class_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Lọc năm học
        |--------------------------------------------------------------------------
        */
        if ($request->filled('school_year_id')) {

            $query->where(
                'school_year_id',
                $request->school_year_id
            );
        }


        $assignments = $query
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();


        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();


        $schoolYears = SchoolYear::orderByDesc('id')
            ->get();


        return view(
            'class_assignments.index',
            compact(
                'assignments',
                'classes',
                'schoolYears'
            )
        );
    }


    /**
     * Form phân công GVCN.
     */
    public function create()
    {
        $teachers = Teacher::orderBy(
            'teacher_code'
        )->get();


        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();


        $schoolYears = SchoolYear::orderByDesc('id')
            ->get();


        return view(
            'class_assignments.create',
            compact(
                'teachers',
                'classes',
                'schoolYears'
            )
        );
    }


    /**
     * Lưu phân công GVCN.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([

            'teacher_id' =>
                'required|exists:teachers,id',

            'class_id' =>
                'required|exists:school_classes,id',

            'school_year_id' =>
                'required|exists:school_years,id',

            'start_date' =>
                'nullable|date',

            'note' =>
                'nullable|string|max:1000',

        ], [

            'teacher_id.required' =>
                'Vui lòng chọn giáo viên.',

            'teacher_id.exists' =>
                'Giáo viên không tồn tại.',

            'class_id.required' =>
                'Vui lòng chọn lớp.',

            'class_id.exists' =>
                'Lớp không tồn tại.',

            'school_year_id.required' =>
                'Vui lòng chọn năm học.',

            'school_year_id.exists' =>
                'Năm học không tồn tại.',

            'start_date.date' =>
                'Ngày bắt đầu không hợp lệ.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | LẤY NĂM HỌC
        |--------------------------------------------------------------------------
        */
        $schoolYear = SchoolYear::findOrFail(
            $request->school_year_id
        );


        /*
        |--------------------------------------------------------------------------
        | NGÀY BẮT ĐẦU
        |--------------------------------------------------------------------------
        |
        | Nếu form không nhập thì mặc định lấy ngày bắt đầu
        | của năm học.
        |
        */
        $startDate = $request->start_date
            ?: $schoolYear->start_date;


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA GIÁO VIÊN ĐÃ LÀM GVCN LỚP KHÁC
        |--------------------------------------------------------------------------
        */
        $teacherExists = ClassAssignment::query()
            ->where('teacher_id', $request->teacher_id)
            ->where('school_year_id', $request->school_year_id)
            ->whereNull('end_date')
            ->exists();


        if ($teacherExists) {

            return back()
                ->withInput()
                ->withErrors([

                    'teacher_id' =>
                        'Giáo viên này đang làm chủ nhiệm một lớp khác trong năm học này.'

                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA PHÂN CÔNG HIỆN TẠI CỦA LỚP
        |--------------------------------------------------------------------------
        */
        $currentAssignment = ClassAssignment::query()
            ->where(
                'class_id',
                $request->class_id
            )
            ->where(
                'school_year_id',
                $request->school_year_id
            )
            ->whereNull('end_date')
            ->latest('id')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | NẾU LỚP ĐÃ CÓ GVCN
        |--------------------------------------------------------------------------
        |
        | Không tạo 2 bản ghi cùng lúc.
        |
        | GVCN cũ sẽ được đóng lại bằng end_date.
        |
        */
        if ($currentAssignment) {

            /*
            | Nếu chính giáo viên đó đang là GVCN
            */
            if (
                (int) $currentAssignment->teacher_id
                ===
                (int) $request->teacher_id
            ) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'teacher_id' =>
                            'Giáo viên này đã là giáo viên chủ nhiệm của lớp này.'

                    ]);
            }


            /*
            | Nếu ngày bắt đầu mới <= ngày bắt đầu cũ
            | thì lấy ngày bắt đầu mới làm ngày kết thúc cũ.
            */
            $oldEndDate = Carbon::parse(
                $startDate
            )->subDay()->format('Y-m-d');


            /*
            | Không cho end_date nhỏ hơn start_date
            */
            if (
                $currentAssignment->start_date
                &&
                Carbon::parse($oldEndDate)
                    ->lt(
                        Carbon::parse(
                            $currentAssignment->start_date
                        )
                    )
            ) {

                return back()
                    ->withInput()
                    ->withErrors([

                        'start_date' =>
                            'Ngày bắt đầu phân công mới phải sau ngày bắt đầu của giáo viên chủ nhiệm cũ.'

                    ]);
            }


            /*
            | Đóng phân công cũ
            */
            $currentAssignment->update([

                'end_date' =>
                    $oldEndDate,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | TẠO PHÂN CÔNG MỚI
        |--------------------------------------------------------------------------
        */
        ClassAssignment::create([

            'teacher_id' =>
                $request->teacher_id,

            'class_id' =>
                $request->class_id,

            'school_year_id' =>
                $request->school_year_id,

            'start_date' =>
                $startDate,

            'end_date' =>
                null,

            'note' =>
                $request->note,

        ]);


        /*
        |--------------------------------------------------------------------------
        | QUAY VỀ DANH SÁCH
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route(
                'class-assignments.index'
            )
            ->with(
                'success',
                'Phân công giáo viên chủ nhiệm thành công!'
            );
    }


    /**
     * Xem chi tiết.
     */
    public function show(
        ClassAssignment $classAssignment
    ) {
        $classAssignment->load([
            'teacher',
            'schoolClass',
            'schoolYear',
        ]);


        return view(
            'class_assignments.show',
            compact(
                'classAssignment'
            )
        );
    }


    /**
     * Form chỉnh sửa.
     */
    public function edit(
        ClassAssignment $classAssignment
    ) {
        $teachers = Teacher::orderBy(
            'teacher_code'
        )->get();


        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();


        $schoolYears = SchoolYear::orderByDesc('id')
            ->get();


        return view(
            'class_assignments.edit',
            compact(
                'classAssignment',
                'teachers',
                'classes',
                'schoolYears'
            )
        );
    }


    /**
     * Cập nhật phân công.
     */
    public function update(
        Request $request,
        ClassAssignment $classAssignment
    ) {
        $validated = $request->validate([

            'teacher_id' =>
                'required|exists:teachers,id',

            'class_id' =>
                'required|exists:school_classes,id',

            'school_year_id' =>
                'required|exists:school_years,id',

            'start_date' =>
                'nullable|date',

            'end_date' =>
                'nullable|date|after_or_equal:start_date',

            'note' =>
                'nullable|string|max:1000',

        ]);


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA GIÁO VIÊN ĐÃ LÀM GVCN LỚP KHÁC
        |--------------------------------------------------------------------------
        */
        $teacherExists = ClassAssignment::query()
            ->where(
                'teacher_id',
                $request->teacher_id
            )
            ->where(
                'school_year_id',
                $request->school_year_id
            )
            ->whereNull('end_date')
            ->where(
                'id',
                '!=',
                $classAssignment->id
            )
            ->exists();


        if ($teacherExists) {

            return back()
                ->withInput()
                ->withErrors([

                    'teacher_id' =>
                        'Giáo viên này đang làm chủ nhiệm một lớp khác trong năm học này.'

                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA LỚP ĐÃ CÓ GVCN KHÁC
        |--------------------------------------------------------------------------
        */
        $classExists = ClassAssignment::query()
            ->where(
                'class_id',
                $request->class_id
            )
            ->where(
                'school_year_id',
                $request->school_year_id
            )
            ->whereNull('end_date')
            ->where(
                'id',
                '!=',
                $classAssignment->id
            )
            ->exists();


        if ($classExists) {

            return back()
                ->withInput()
                ->withErrors([

                    'class_id' =>
                        'Lớp này đã có giáo viên chủ nhiệm khác trong năm học này.'

                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CẬP NHẬT
        |--------------------------------------------------------------------------
        */
        $classAssignment->update([

            'teacher_id' =>
                $request->teacher_id,

            'class_id' =>
                $request->class_id,

            'school_year_id' =>
                $request->school_year_id,

            'start_date' =>
                $request->start_date,

            'end_date' =>
                $request->end_date,

            'note' =>
                $request->note,

        ]);


        return redirect()
            ->route(
                'class-assignments.index'
            )
            ->with(
                'success',
                'Cập nhật phân công giáo viên chủ nhiệm thành công!'
            );
    }


    /**
     * Xóa phân công.
     */
    public function destroy(
        ClassAssignment $classAssignment
    ) {
        $classAssignment->delete();


        return redirect()
            ->route(
                'class-assignments.index'
            )
            ->with(
                'success',
                'Xóa phân công giáo viên chủ nhiệm thành công!'
            );
    }
}