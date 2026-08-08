<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentHealth;
use Illuminate\Http\Request;

class StudentHealthController extends Controller
{
    /**
     * Lưu / cập nhật hồ sơ sức khỏe của học sinh
     */
    public function store(Request $request, Student $student)
    {
        $request->validate([
            'height' => 'nullable|numeric|min:0|max:300',
            'weight' => 'nullable|numeric|min:0|max:500',
            'blood_type' => 'nullable|in:A,B,AB,O',
            'allergies' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
        ], [
            'height.numeric' => 'Chiều cao phải là số.',
            'height.min' => 'Chiều cao không hợp lệ.',
            'height.max' => 'Chiều cao không được vượt quá 300 cm.',

            'weight.numeric' => 'Cân nặng phải là số.',
            'weight.min' => 'Cân nặng không hợp lệ.',
            'weight.max' => 'Cân nặng không được vượt quá 500 kg.',

            'blood_type.in' => 'Nhóm máu không hợp lệ.',
        ]);

        StudentHealth::updateOrCreate(
            [
                'student_id' => $student->id,
            ],
            [
                'height' => $request->height,
                'weight' => $request->weight,
                'blood_type' => $request->blood_type,
                'allergies' => $request->allergies,
                'notes' => $request->notes,
            ]
        );

        return redirect()
            ->route('students.show', $student)
            ->with(
                'success',
                'Cập nhật hồ sơ sức khỏe thành công!'
            );
    }

    /**
     * Xóa hồ sơ sức khỏe
     */
    public function destroy(StudentHealth $studentHealth)
    {
        $student = $studentHealth->student;

        $studentHealth->delete();

        return redirect()
            ->route('students.show', $student)
            ->with(
                'success',
                'Xóa hồ sơ sức khỏe thành công!'
            );
    }
}