<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentClassHistory;
use App\Models\SchoolClass;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class StudentClassHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentClassHistory::with([
            'student',
            'schoolClass',
            'schoolYear'
        ]);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('school_year_id')) {
            $query->where('school_year_id', $request->school_year_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $histories = $query
            ->orderByDesc('school_year_id')
            ->orderBy('student_id')
            ->paginate(20)
            ->withQueryString();

        $students = Student::orderBy('full_name')->get();

        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();

        $schoolYears = SchoolYear::orderByDesc('id')->get();

        return view('student_class_histories.index', compact(
            'histories',
            'students',
            'classes',
            'schoolYears'
        ));
    }

    public function create()
    {
        $students = Student::orderBy('full_name')->get();

        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();

        $schoolYears = SchoolYear::orderByDesc('id')->get();

        return view('student_class_histories.create', compact(
            'students',
            'classes',
            'schoolYears'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:school_classes,id',
            'school_year_id' => 'required|exists:school_years,id',
            'status' => 'required|string|max:50',
            'note' => 'nullable|string|max:1000',
        ]);

        StudentClassHistory::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'school_year_id' => $validated['school_year_id'],
            ],
            [
                'class_id' => $validated['class_id'],
                'status' => $validated['status'],
                'note' => $validated['note'] ?? null,
            ]
        );

        return redirect()
            ->route('student-class-histories.index')
            ->with('success', 'Đã lưu lịch sử lớp học sinh.');
    }

    public function edit(StudentClassHistory $studentClassHistory)
    {
        $students = Student::orderBy('full_name')->get();

        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();

        $schoolYears = SchoolYear::orderByDesc('id')->get();

        return view('student_class_histories.edit', compact(
            'studentClassHistory',
            'students',
            'classes',
            'schoolYears'
        ));
    }

    public function update(
        Request $request,
        StudentClassHistory $studentClassHistory
    ) {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:school_classes,id',
            'school_year_id' => 'required|exists:school_years,id',
            'status' => 'required|string|max:50',
            'note' => 'nullable|string|max:1000',
        ]);

        $studentClassHistory->update([
            'student_id' => $validated['student_id'],
            'class_id' => $validated['class_id'],
            'school_year_id' => $validated['school_year_id'],
            'status' => $validated['status'],
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()
            ->route('student-class-histories.index')
            ->with('success', 'Cập nhật lịch sử thành công.');
    }

    public function destroy(StudentClassHistory $studentClassHistory)
    {
        $studentClassHistory->delete();

        return redirect()
            ->route('student-class-histories.index')
            ->with('success', 'Đã xóa lịch sử.');
    }
}