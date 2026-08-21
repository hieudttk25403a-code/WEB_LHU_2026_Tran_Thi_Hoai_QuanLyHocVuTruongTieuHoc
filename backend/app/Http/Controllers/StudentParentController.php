<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentParent;
use Illuminate\Http\Request;

class StudentParentController extends Controller
{
    /**
     * Form thêm phụ huynh
     */
    public function create(Student $student)
    {
        return view('student_parents.create', compact('student'));
    }

    /**
     * Lưu phụ huynh
     */
    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:100',
            'occupation' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ], [
            'full_name.required' => 'Vui lòng nhập họ tên phụ huynh.',
            'relationship.required' => 'Vui lòng chọn mối quan hệ.',
            'email.email' => 'Email không đúng định dạng.',
        ]);

        $studentParent = new StudentParent();

        $studentParent->student_id = $student->id;
        $studentParent->full_name = $validated['full_name'];
        $studentParent->relationship = $validated['relationship'];
        $studentParent->occupation = $validated['occupation'] ?? null;
        $studentParent->phone = $validated['phone'] ?? null;
        $studentParent->email = $validated['email'] ?? null;
        $studentParent->address = $validated['address'] ?? null;

        $studentParent->save();

        return redirect()
            ->route('students.show', $student->id)
            ->with('success', 'Thêm thông tin phụ huynh thành công!');
    }

    /**
     * Form chỉnh sửa phụ huynh
     */
    public function edit(Student $student, StudentParent $parent)
    {
        return view('student_parents.edit', compact(
            'student',
            'parent'
        ));
    }

    /**
     * Cập nhật phụ huynh
     */
    public function update(
        Request $request,
        Student $student,
        StudentParent $parent
    ) {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:100',
            'occupation' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ], [
            'full_name.required' => 'Vui lòng nhập họ tên phụ huynh.',
            'relationship.required' => 'Vui lòng chọn mối quan hệ.',
            'email.email' => 'Email không đúng định dạng.',
        ]);

        $parent->student_id = $student->id;
        $parent->full_name = $validated['full_name'];
        $parent->relationship = $validated['relationship'];
        $parent->occupation = $validated['occupation'] ?? null;
        $parent->phone = $validated['phone'] ?? null;
        $parent->email = $validated['email'] ?? null;
        $parent->address = $validated['address'] ?? null;

        $parent->save();

        return redirect()
            ->route('students.show', $student->id)
            ->with('success', 'Cập nhật thông tin phụ huynh thành công!');
    }

    /**
     * Xóa phụ huynh
     */
    public function destroy(Student $student, StudentParent $parent)
    {
        $parent->delete();

        return redirect()
            ->route('students.show', $student->id)
            ->with('success', 'Xóa thông tin phụ huynh thành công!');
    }
}