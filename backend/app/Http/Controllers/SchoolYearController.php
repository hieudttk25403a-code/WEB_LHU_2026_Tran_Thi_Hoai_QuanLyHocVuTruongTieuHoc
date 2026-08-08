<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    /**
     * Hiển thị danh sách năm học
     */
    public function index()
    {
        $schoolYears = SchoolYear::latest()->paginate(10);

        return view('school-years.index', compact('schoolYears'));
    }

    /**
     * Hiển thị form thêm năm học
     */
    public function create()
    {
        return view('school-years.create');
    }

    /**
     * Lưu năm học mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Vui lòng nhập tên năm học.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' =>
                'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ]);

        SchoolYear::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('school-years.index')
            ->with('success', 'Thêm năm học thành công!');
    }

    /**
     * Hiển thị chi tiết năm học
     */
    public function show(SchoolYear $schoolYear)
    {
        return view('school-years.show', compact('schoolYear'));
    }

    /**
     * Hiển thị form sửa năm học
     */
    public function edit(SchoolYear $schoolYear)
    {
        return view('school-years.edit', compact('schoolYear'));
    }

    /**
     * Cập nhật năm học
     */
    public function update(Request $request, SchoolYear $schoolYear)
    {
        $request->validate([
            'name' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Vui lòng nhập tên năm học.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' =>
                'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ]);

        $schoolYear->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('school-years.index')
            ->with('success', 'Cập nhật năm học thành công!');
    }

    /**
     * Xóa năm học
     */
    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYear->delete();

        return redirect()
            ->route('school-years.index')
            ->with('success', 'Xóa năm học thành công!');
    }
}