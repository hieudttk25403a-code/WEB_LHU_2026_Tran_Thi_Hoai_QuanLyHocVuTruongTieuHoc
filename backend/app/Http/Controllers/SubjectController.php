<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index(Request $request)
{
    $query = Subject::query();

    if ($request->filled('keyword')) {

        $query->where('subject_code', 'like', '%' . $request->keyword . '%')
              ->orWhere('subject_name', 'like', '%' . $request->keyword . '%')
              ->orWhere('teacher', 'like', '%' . $request->keyword . '%')
              ->orWhere('grade', 'like', '%' . $request->keyword . '%');

    }

    $subjects = $query->latest()
                      ->paginate(10)
                      ->withQueryString();

    return view('subjects.index', compact('subjects'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
{
    $request->validate([
        'subject_code' => 'required|unique:subjects,subject_code',
        'subject_name' => 'required',
        'grade' => 'required',
        'status' => 'required',
    ], [
        'subject_code.required' => 'Vui lòng nhập mã môn.',
        'subject_code.unique' => 'Mã môn này đã tồn tại.',
        'subject_name.required' => 'Vui lòng nhập tên môn.',
        'grade.required' => 'Vui lòng chọn khối.',
    ]);

    Subject::create([
        'subject_code' => $request->subject_code,
        'subject_name' => $request->subject_name,
        'teacher' => $request->teacher,
        'grade' => $request->grade,
        'status' => $request->status,
    ]);

    return redirect()
        ->route('subjects.index')
        ->with('success', 'Thêm môn học thành công!');
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
public function show(Subject $subject)
{
    return view('subjects.show', compact('subject'));
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
public function edit(Subject $subject)
{
    return view('subjects.edit', compact('subject'));
}
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
public function update(Request $request, Subject $subject)
{
    $request->validate([
        'subject_code' => 'required|unique:subjects,subject_code,' . $subject->id,
        'subject_name' => 'required',
        'grade' => 'required',
        'status' => 'required',
    ], [
        'subject_code.required' => 'Vui lòng nhập mã môn.',
        'subject_code.unique' => 'Mã môn này đã tồn tại.',
        'subject_name.required' => 'Vui lòng nhập tên môn.',
        'grade.required' => 'Vui lòng chọn khối.',
    ]);

    $subject->update([
        'subject_code' => $request->subject_code,
        'subject_name' => $request->subject_name,
        'teacher' => $request->teacher,
        'grade' => $request->grade,
        'status' => $request->status,
    ]);

    return redirect()
        ->route('subjects.index')
        ->with('success', 'Cập nhật môn học thành công!');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
public function destroy(Subject $subject)
{
    $subject->delete();

    return redirect()
        ->route('subjects.index')
        ->with('success', 'Xóa môn học thành công!');
}
}
