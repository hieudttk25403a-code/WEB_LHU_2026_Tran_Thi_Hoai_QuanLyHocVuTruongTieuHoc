<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentHealth;
use Illuminate\Http\Request;

class StudentHealthController extends Controller
{
    /**
     * Form tạo hồ sơ sức khỏe
     */
    public function create(Student $student)
    {
        // Nếu đã có hồ sơ thì chuyển sang sửa
        if ($student->health) {
            return redirect()
                ->route('student-health.edit', $student);
        }

        return view('student_health.create', compact('student'));
    }

    /**
     * Lưu hồ sơ sức khỏe
     */
    public function store(Request $request, Student $student)
    {
        $validated = $request->validate([
            'height' => 'nullable|numeric|min:0|max:250',
            'weight' => 'nullable|numeric|min:0|max:200',
            'blood_group' => 'nullable|string|max:20',
            'allergy' => 'nullable|string|max:1000',
            'note' => 'nullable|string|max:2000',
        ]);

        $student->health()->updateOrCreate(
            ['student_id' => $student->id],
            $validated
        );

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Lưu hồ sơ sức khỏe thành công!');
    }

    /**
     * Form chỉnh sửa
     */
    public function edit(Student $student)
    {
        $health = $student->health;

        if (!$health) {
            return redirect()
                ->route('student-health.create', $student);
        }

        return view('student_health.edit', compact(
            'student',
            'health'
        ));
    }

    /**
     * Cập nhật
     */
    public function update(
        Request $request,
        Student $student
    ) {
        $validated = $request->validate([
            'height' => 'nullable|numeric|min:0|max:250',
            'weight' => 'nullable|numeric|min:0|max:200',
            'blood_group' => 'nullable|string|max:20',
            'allergy' => 'nullable|string|max:1000',
            'note' => 'nullable|string|max:2000',
        ]);

        $health = $student->health;

        if (!$health) {
            $health = new StudentHealth();
            $health->student_id = $student->id;
        }

        $health->fill($validated);
        $health->save();

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Cập nhật hồ sơ sức khỏe thành công!');
    }

    /**
     * Xóa hồ sơ
     */
    public function destroy(Student $student)
    {
        if ($student->health) {
            $student->health->delete();
        }

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Xóa hồ sơ sức khỏe thành công!');
    }
}