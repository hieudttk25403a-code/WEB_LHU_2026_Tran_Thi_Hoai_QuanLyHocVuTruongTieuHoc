<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentParent;
use Illuminate\Http\Request;

class StudentParentController extends Controller
{
    /**
     * Thêm phụ huynh cho học sinh
     */
public function store(Request $request, Student $student)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'relationship' => 'required|string|max:100',
        'occupation' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
    ], [
        'full_name.required' => 'Vui lòng nhập họ tên phụ huynh.',
        'relationship.required' => 'Vui lòng chọn quan hệ với học sinh.',
        'email.email' => 'Email không đúng định dạng.',
    ]);

    StudentParent::create([
        'student_id' => $student->id,
        'full_name' => $request->full_name,
        'relationship' => $request->relationship,
        'occupation' => $request->occupation,
        'phone' => $request->phone,
        'email' => $request->email,
    ]);

    return redirect()
        ->route('students.show', $student)
        ->with('success', 'Thêm phụ huynh thành công!');
}

    /**
     * Cập nhật phụ huynh
     */
    public function update(
        Request $request,
        Student $student,
        StudentParent $parent
    ) {
        $request->validate([
            'full_name' => 'required',
            'relationship' => 'required',
            'occupation' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
        ], [
            'full_name.required' => 'Vui lòng nhập họ tên phụ huynh.',
            'relationship.required' => 'Vui lòng nhập quan hệ với học sinh.',
            'email.email' => 'Email không đúng định dạng.',
        ]);

        $parent->update([
            'full_name' => $request->full_name,
            'relationship' => $request->relationship,
            'occupation' => $request->occupation,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Cập nhật phụ huynh thành công!');
    }


    /**
     * Xóa phụ huynh
     */
    public function destroy(
        Student $student,
        StudentParent $parent
    ) {
        $parent->delete();

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Xóa phụ huynh thành công!');
    }
}