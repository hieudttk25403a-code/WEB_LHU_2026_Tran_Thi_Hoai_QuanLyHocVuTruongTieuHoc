<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class BghTeacherController extends Controller
{
    /**
     * Danh sách giáo viên dành cho Ban Giám Hiệu
     */
    public function index(Request $request)
    {
        $query = Teacher::query();

        /*
        |--------------------------------------------------------------------------
        | TÌM KIẾM
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'teacher_code',
                    'like',
                    '%' . $keyword . '%'
                )
                ->orWhere(
                    'full_name',
                    'like',
                    '%' . $keyword . '%'
                )
                ->orWhere(
                    'specialization',
                    'like',
                    '%' . $keyword . '%'
                )
                ->orWhere(
                    'department',
                    'like',
                    '%' . $keyword . '%'
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | LỌC LOẠI GIÁO VIÊN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('teacher_type')) {

            $query->where(
                'teacher_type',
                $request->teacher_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LẤY DANH SÁCH
        |--------------------------------------------------------------------------
        */

        $teachers = $query
            ->orderBy('full_name')
            ->paginate(15)
            ->withQueryString();

        return view(
            'bgh.teachers.index',
            compact('teachers')
        );
    }


    /**
     * Xem chi tiết giáo viên
     */
    public function show(Teacher $teacher)
    {
        return view(
            'bgh.teachers.show',
            compact('teacher')
        );
    }
}