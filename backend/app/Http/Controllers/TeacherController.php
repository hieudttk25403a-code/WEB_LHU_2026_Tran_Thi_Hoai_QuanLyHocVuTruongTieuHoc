<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Hiển thị danh sách giáo viên.
     */
    public function index(Request $request)
    {
        $query = Teacher::query();

        if ($request->filled('keyword')) {
            $query->where('teacher_code', 'like', '%' . $request->keyword . '%')
                  ->orWhere('full_name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%');
        }

        $teachers = $query->latest()
                          ->paginate(10)
                          ->withQueryString();

        return view('teachers.index', compact('teachers'));
    }

    /**
     * Hiển thị form thêm giáo viên.
     */
    public function create()
    {
        return view('teachers.create');
    }

    /**
     * Lưu giáo viên mới.
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_code'   => 'required|unique:teachers',
            'full_name'      => 'required',
            'specialization' => 'required',
            'email'          => 'nullable|email',
        ]);

        Teacher::create($request->all());

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Thêm giáo viên thành công!');
    }

    /**
     * Hiển thị chi tiết giáo viên.
     */
public function show(Teacher $teacher)
{
    return view('teachers.show', compact('teacher'));
}

    /**
     * Hiển thị form sửa giáo viên.
     */
    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Cập nhật giáo viên.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'teacher_code'   => 'required|unique:teachers,teacher_code,' . $teacher->id,
            'full_name'      => 'required',
            'specialization' => 'required',
            'email'          => 'nullable|email',
        ]);

        $teacher->update($request->all());

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Cập nhật giáo viên thành công!');
    }

    /**
     * Xóa giáo viên.
     */
public function destroy(Teacher $teacher)
{
    $teacher->delete();

    return redirect()
            ->route('teachers.index')
            ->with('success', 'Xóa giáo viên thành công!');
}
}