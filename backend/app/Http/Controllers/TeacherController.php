<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::query();

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where(
                    'teacher_code',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'full_name',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'specialization',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'email',
                    'like',
                    "%{$keyword}%"
                );
            });
        }

        $teachers = $query
            ->with([
                'classAssignments.schoolClass',
                'classAssignments.schoolYear',
                'subjectAssignments.subject',
                'subjectAssignments.schoolClass',
                'subjectAssignments.schoolYear',
            ])
            ->orderBy('teacher_code')
            ->paginate(10)
            ->withQueryString();

        foreach ($teachers as $teacher) {

            $teacher->is_specialist =
                $teacher->isSpecialist();

            $teacher->specialist_subject =
                $teacher->specialistSubjectName();

            /*
            |--------------------------------------------------------------------------
            | Chỉ tính các phân công còn hiệu lực
            |--------------------------------------------------------------------------
            */

            $teacher->active_homerooms =
                $teacher->classAssignments->filter(
                    fn ($a) =>
                        is_null($a->end_date)
                        || $a->end_date->gte(now()->startOfDay())
                );

            $teacher->active_subjects =
                $teacher->subjectAssignments->filter(
                    fn ($a) =>
                        is_null($a->end_date)
                        || $a->end_date->gte(now()->startOfDay())
                );

            $teacher->has_homeroom =
                $teacher->active_homerooms->isNotEmpty();

            $teacher->has_subject =
                $teacher->active_subjects->isNotEmpty();

            /*
            |--------------------------------------------------------------------------
            | MÀU GIÁO VIÊN
            |--------------------------------------------------------------------------
            */

            if ($teacher->is_specialist) {
                $teacher->teacher_type = 'specialist';
            } elseif (
                $teacher->has_homeroom
                && $teacher->has_subject
            ) {
                $teacher->teacher_type = 'homeroom_subject';
            } elseif ($teacher->has_subject) {
                $teacher->teacher_type = 'subject';
            } elseif ($teacher->has_homeroom) {
                $teacher->teacher_type = 'homeroom';
            } else {
                $teacher->teacher_type = 'none';
            }
        }

        return view(
            'teachers.index',
            compact('teachers')
        );
    }

    public function assignment()
    {
        return view('teachers.assignment');
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_code' =>
                'required|string|max:50|unique:teachers,teacher_code',

            'full_name' =>
                'required|string|max:255',

            'gender' =>
                'nullable|in:Nam,Nữ',

            'phone' =>
                'nullable|string|max:30',

            'email' =>
                'nullable|email|max:255',

            'department' =>
                'nullable|string|max:255',

            'status' =>
                'required|string|max:100',
        ]);

        $code = strtoupper(
            trim($validated['teacher_code'])
        );

        /*
        |--------------------------------------------------------------------------
        | Mã GV phải đúng chuẩn
        |--------------------------------------------------------------------------
        */

        if (
            !preg_match('/^GV\d+$/', $code)
            && !preg_match('/^GVCA\d+$/', $code)
            && !preg_match('/^GVTH\d+$/', $code)
            && !preg_match('/^GVCT\d+$/', $code)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'teacher_code' =>
                        'Mã giáo viên phải có dạng GV001, GVCA001 hoặc GVTH001.'
                ]);
        }

        $validated['teacher_code'] = $code;

        /*
        |--------------------------------------------------------------------------
        | Tự động chuyên môn
        |--------------------------------------------------------------------------
        */

        if (
            preg_match('/^GVCA\d+$/', $code)
        ) {
            $validated['specialization'] =
                'Giáo viên chuyên Tiếng Anh';
        } elseif (
            preg_match('/^(GVTH|GVCT)\d+$/', $code)
        ) {
            $validated['specialization'] =
                'Giáo viên chuyên Tin học';
        } else {
            $validated['specialization'] =
                'Giáo viên tiểu học';
        }

        Teacher::create($validated);

        return redirect()
            ->route('teachers.index')
            ->with(
                'success',
                'Thêm giáo viên thành công!'
            );
    }

    public function show(Teacher $teacher)
    {
        $teacher->load([
            'classAssignments.schoolClass',
            'classAssignments.schoolYear',
            'subjectAssignments.subject',
            'subjectAssignments.schoolClass',
            'subjectAssignments.schoolYear',
        ]);

        return view(
            'teachers.show',
            compact('teacher')
        );
    }

    public function edit(Teacher $teacher)
    {
        return view(
            'teachers.edit',
            compact('teacher')
        );
    }

    public function update(
        Request $request,
        Teacher $teacher
    ) {
        $validated = $request->validate([
            'teacher_code' =>
                'required|string|max:50|unique:teachers,teacher_code,' . $teacher->id,

            'full_name' =>
                'required|string|max:255',

            'gender' =>
                'nullable|in:Nam,Nữ',

            'phone' =>
                'nullable|string|max:30',

            'email' =>
                'nullable|email|max:255',

            'department' =>
                'nullable|string|max:255',

            'status' =>
                'required|string|max:100',
        ]);

        $code = strtoupper(
            trim($validated['teacher_code'])
        );

        if (
            !preg_match('/^GV\d+$/', $code)
            && !preg_match('/^GVCA\d+$/', $code)
            && !preg_match('/^GVTH\d+$/', $code)
            && !preg_match('/^GVCT\d+$/', $code)
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'teacher_code' =>
                        'Mã giáo viên không đúng định dạng.'
                ]);
        }

        $validated['teacher_code'] = $code;

        if (preg_match('/^GVCA\d+$/', $code)) {
            $validated['specialization'] =
                'Giáo viên chuyên Tiếng Anh';
        } elseif (
            preg_match('/^(GVTH|GVCT)\d+$/', $code)
        ) {
            $validated['specialization'] =
                'Giáo viên chuyên Tin học';
        } else {
            $validated['specialization'] =
                'Giáo viên tiểu học';
        }

        $teacher->update($validated);

        return redirect()
            ->route('teachers.index')
            ->with(
                'success',
                'Cập nhật giáo viên thành công!'
            );
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()
            ->route('teachers.index')
            ->with(
                'success',
                'Xóa giáo viên thành công!'
            );
    }
}