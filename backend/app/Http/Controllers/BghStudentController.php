<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class BghStudentController extends Controller
{
    /**
     * Danh sách học sinh dành cho Ban Giám Hiệu
     */
    public function index(Request $request)
    {
        $query = Student::with([
            'schoolClass',
            'classHistories.schoolClass',
            'classHistories.schoolYear',
        ]);

        /*
        |--------------------------------------------------------------------------
        | TÌM KIẾM THEO TÊN / MÃ HỌC SINH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {

            $keyword = trim(
                $request->keyword
            );

            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'full_name',
                    'like',
                    '%' . $keyword . '%'
                )
                ->orWhere(
                    'student_code',
                    'like',
                    '%' . $keyword . '%'
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | LỌC THEO LỚP
        |--------------------------------------------------------------------------
        */

        if ($request->filled('class_id')) {

            $query->where(
                'class_id',
                $request->class_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LỌC THEO TRẠNG THÁI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DANH SÁCH
        |--------------------------------------------------------------------------
        */

        $students = $query
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | DANH SÁCH LỚP
        |--------------------------------------------------------------------------
        */

        $classes = SchoolClass::orderBy('grade')
            ->orderBy('class_name')
            ->get();

        return view(
            'bgh.students.index',
            compact(
                'students',
                'classes'
            )
        );
    }


    /**
     * Xem chi tiết học sinh dành cho BGH
     */
    public function show(Student $student)
    {
        $student->load([
            'schoolClass',

            'classHistories.schoolClass',
            'classHistories.schoolYear',

            'parents',

            'healthProfile',

            'scores.subject',
            'scores.schoolYear',
        ]);

        return view(
            'bgh.students.show',
            compact('student')
        );
    }
}