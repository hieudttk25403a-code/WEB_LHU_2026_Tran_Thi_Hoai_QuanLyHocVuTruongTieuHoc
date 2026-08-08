<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Models\SchoolClass;

class TimetableController extends Controller
{
    /**
     * Danh sách thời khóa biểu
     */
    public function index(Request $request)
    {
        $query = Timetable::with([
            'schoolClass',
            'teacher',
            'subject',
            'schoolYear'
        ]);

        // Lọc theo lớp
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Lọc theo giáo viên
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Lọc theo năm học
        if ($request->filled('school_year_id')) {
            $query->where('school_year_id', $request->school_year_id);
        }

        $timetables = $query
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->paginate(15)
            ->withQueryString();

        $classes = SchoolClass::orderBy('class_name')->get();

        $teachers = Teacher::orderBy('full_name')->get();

        $schoolYears = SchoolYear::orderByDesc('name')->get();

        return view('timetables.index', compact(
            'timetables',
            'classes',
            'teachers',
            'schoolYears'
        ));
    }


    /**
     * Form thêm thời khóa biểu
     */
    public function create()
    {
        $classes = SchoolClass::orderBy('class_name')->get();

        $teachers = Teacher::orderBy('full_name')->get();

        $subjects = Subject::orderBy('subject_name')->get();

        $schoolYears = SchoolYear::orderByDesc('name')->get();

        return view('timetables.create', compact(
            'classes',
            'teachers',
            'subjects',
            'schoolYears'
        ));
    }


    /**
     * Lưu thời khóa biểu
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'school_year_id' => 'required|exists:school_years,id',
            'day_of_week' => 'required|in:Thứ 2,Thứ 3,Thứ 4,Thứ 5,Thứ 6,Thứ 7',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'nullable|string|max:100',
        ], [
            'class_id.required' => 'Vui lòng chọn lớp.',
            'class_id.exists' => 'Lớp không tồn tại.',

            'teacher_id.required' => 'Vui lòng chọn giáo viên.',
            'teacher_id.exists' => 'Giáo viên không tồn tại.',

            'subject_id.required' => 'Vui lòng chọn môn học.',
            'subject_id.exists' => 'Môn học không tồn tại.',

            'school_year_id.required' => 'Vui lòng chọn năm học.',
            'school_year_id.exists' => 'Năm học không tồn tại.',

            'day_of_week.required' => 'Vui lòng chọn thứ.',

            'start_time.required' => 'Vui lòng chọn giờ bắt đầu.',

            'end_time.required' => 'Vui lòng chọn giờ kết thúc.',
            'end_time.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
        ]);

        Timetable::create([
            'class_id' => $request->class_id,
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'school_year_id' => $request->school_year_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'room' => $request->room,
        ]);

        return redirect()
            ->route('timetables.index')
            ->with('success', 'Thêm thời khóa biểu thành công!');
    }


    /**
     * Xem chi tiết
     */
    public function show(Timetable $timetable)
    {
        $timetable->load([
            'schoolClass',
            'teacher',
            'subject',
            'schoolYear'
        ]);

        return view('timetables.show', compact('timetable'));
    }


    /**
     * Form chỉnh sửa
     */
    public function edit(Timetable $timetable)
    {
        $classes = SchoolClass::orderBy('class_name')->get();

        $teachers = Teacher::orderBy('full_name')->get();

        $subjects = Subject::orderBy('subject_name')->get();

        $schoolYears = SchoolYear::orderByDesc('name')->get();

        return view('timetables.edit', compact(
            'timetable',
            'classes',
            'teachers',
            'subjects',
            'schoolYears'
        ));
    }


    /**
     * Cập nhật thời khóa biểu
     */
    public function update(Request $request, Timetable $timetable)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'school_year_id' => 'required|exists:school_years,id',
            'day_of_week' => 'required|in:Thứ 2,Thứ 3,Thứ 4,Thứ 5,Thứ 6,Thứ 7',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room' => 'nullable|string|max:100',
        ]);

        $timetable->update([
            'class_id' => $request->class_id,
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'school_year_id' => $request->school_year_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'room' => $request->room,
        ]);

        return redirect()
            ->route('timetables.index')
            ->with('success', 'Cập nhật thời khóa biểu thành công!');
    }


    /**
     * Xóa thời khóa biểu
     */
    public function destroy(Timetable $timetable)
    {
        $timetable->delete();

        return redirect()
            ->route('timetables.index')
            ->with('success', 'Xóa thời khóa biểu thành công!');
    }
}