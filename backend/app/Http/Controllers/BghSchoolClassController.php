<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;

class BghSchoolClassController extends Controller
{
    /**
     * Danh sách lớp học dành cho Ban Giám Hiệu
     */
    public function index(Request $request)
    {
        $query = SchoolClass::query();

        /*
        |--------------------------------------------------------------------------
        | TÌM KIẾM THEO TÊN LỚP
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $query->where(
                'class_name',
                'like',
                '%' . $keyword . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LỌC THEO KHỐI
        |--------------------------------------------------------------------------
        */

        if ($request->filled('grade')) {

            $query->where(
                'grade',
                $request->grade
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LẤY DANH SÁCH
        |--------------------------------------------------------------------------
        */

        $classes = $query
            ->orderBy('grade')
            ->orderBy('class_name')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | DANH SÁCH KHỐI
        |--------------------------------------------------------------------------
        */

        $grades = SchoolClass::query()
            ->whereNotNull('grade')
            ->distinct()
            ->orderBy('grade')
            ->pluck('grade');

        return view(
            'bgh.classes.index',
            compact(
                'classes',
                'grades'
            )
        );
    }


    /**
     * Xem chi tiết lớp
     */
    public function show(SchoolClass $schoolClass)
    {
        /*
        |--------------------------------------------------------------------------
        | LẤY HỌC SINH THUỘC LỚP
        |--------------------------------------------------------------------------
        */

        $students = $schoolClass->students()
            ->orderBy('full_name')
            ->paginate(20);

        return view(
            'bgh.classes.show',
            compact(
                'schoolClass',
                'students'
            )
        );
    }
}