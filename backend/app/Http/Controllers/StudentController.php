<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index(Request $request)
{
    $query = Student::query();

    if ($request->filled('keyword')) {

        $query->where('student_code', 'like', '%' . $request->keyword . '%')
              ->orWhere('full_name', 'like', '%' . $request->keyword . '%')
              ->orWhere('email', 'like', '%' . $request->keyword . '%');
    }

    $students = $query->latest()
                      ->paginate(10)
                      ->withQueryString();

    return view('students.index', compact('students'));
}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('students.create');
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
            'student_code' => 'required|unique:students',
            'full_name' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            'email' => 'nullable|email',
        ]);

        Student::create($request->all());

        return redirect()
            ->route('students.index')
            ->with('success', 'Thêm học sinh thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function show(Student $student)
{
    $student->load('parents');

    return view('students.show', compact('student'));
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'student_code' => 'required|unique:students,student_code,' . $student->id,
            'full_name' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            'email' => 'nullable|email',
        ]);

        $student->update($request->all());

        return redirect()
            ->route('students.index')
            ->with('success', 'Cập nhật học sinh thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success','Xóa học sinh thành công!');
    }
}