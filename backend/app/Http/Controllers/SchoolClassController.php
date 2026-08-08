<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index(Request $request)
{
    $query = SchoolClass::query();

    if ($request->filled('keyword')) {
        $query->where('class_name', 'like', '%' . $request->keyword . '%')
              ->orWhere('grade', 'like', '%' . $request->keyword . '%');
    }

    $classes = $query->latest()
                     ->paginate(10)
                     ->withQueryString();

    return view('classes.index', compact('classes'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('classes.create');
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
        'class_name' => 'required',
        'grade' => 'required',
        'student_count' => 'required|integer|min:0',
        'status' => 'required',
    ], [
        'class_name.required' => 'Vui lòng nhập tên lớp.',
        'grade.required' => 'Vui lòng chọn khối.',
        'student_count.required' => 'Vui lòng nhập sĩ số.',
        'student_count.integer' => 'Sĩ số phải là số.',
        'student_count.min' => 'Sĩ số không được nhỏ hơn 0.',
    ]);

    SchoolClass::create([
        'class_name' => $request->class_name,
        'grade' => $request->grade,
        'homeroom_teacher' => $request->homeroom_teacher,
        'student_count' => $request->student_count,
        'status' => $request->status,
    ]);

    return redirect()
        ->route('classes.index')
        ->with('success', 'Thêm lớp thành công!');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function show(SchoolClass $class)
{
    return view('classes.show', compact('class'));
}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function edit(SchoolClass $class)
{
    return view('classes.edit', compact('class'));
}
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function update(Request $request, SchoolClass $class)
{
    $request->validate([
        'class_name' => 'required',
        'grade' => 'required',
        'student_count' => 'required|integer|min:0',
        'status' => 'required',
    ], [
        'class_name.required' => 'Vui lòng nhập tên lớp.',
        'grade.required' => 'Vui lòng chọn khối.',
        'student_count.required' => 'Vui lòng nhập sĩ số.',
        'student_count.integer' => 'Sĩ số phải là số.',
        'student_count.min' => 'Sĩ số không được nhỏ hơn 0.',
    ]);

    $class->update([
        'class_name' => $request->class_name,
        'grade' => $request->grade,
        'homeroom_teacher' => $request->homeroom_teacher,
        'student_count' => $request->student_count,
        'status' => $request->status,
    ]);

    return redirect()
        ->route('classes.index')
        ->with('success', 'Cập nhật lớp thành công!');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function destroy(SchoolClass $class)
{
    $class->delete();

    return redirect()
        ->route('classes.index')
        ->with('success', 'Xóa lớp thành công!');
}
}
