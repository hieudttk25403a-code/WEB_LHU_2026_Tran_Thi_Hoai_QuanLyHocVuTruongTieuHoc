<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query =
            Subject::withCount([
                'assignments as teachers_count'
            ]);

        if ($request->filled('keyword')) {

            $keyword =
                trim($request->keyword);

            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'subject_code',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'subject_name',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'grade',
                    'like',
                    "%{$keyword}%"
                );

            });
        }

        $subjects =
            $query
                ->orderBy('subject_name')
                ->paginate(15)
                ->withQueryString();

        return view(
            'subjects.index',
            compact('subjects')
        );
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $validated =
            $request->validate([
                'subject_code' =>
                    'required|string|max:50|unique:subjects,subject_code',

                'subject_name' =>
                    'required|string|max:255',

                'grade' =>
                    'required|string|max:50',
            ]);

        Subject::create($validated);

        return redirect()
            ->route('subjects.index')
            ->with(
                'success',
                'Thêm môn học thành công!'
            );
    }

    public function show(Subject $subject)
    {
        $subject->load([
            'assignments.teacher',
            'assignments.schoolClass',
            'assignments.schoolYear',
        ]);

        return view(
            'subjects.show',
            compact('subject')
        );
    }

    public function edit(Subject $subject)
    {
        return view(
            'subjects.edit',
            compact('subject')
        );
    }

    public function update(
        Request $request,
        Subject $subject
    ) {
        $validated =
            $request->validate([
                'subject_code' =>
                    'required|string|max:50|unique:subjects,subject_code,' . $subject->id,

                'subject_name' =>
                    'required|string|max:255',

                'grade' =>
                    'required|string|max:50',
            ]);

        $subject->update($validated);

        return redirect()
            ->route('subjects.index')
            ->with(
                'success',
                'Cập nhật môn học thành công!'
            );
    }

    public function destroy(
        Subject $subject
    ) {
        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with(
                'success',
                'Xóa môn học thành công!'
            );
    }
}