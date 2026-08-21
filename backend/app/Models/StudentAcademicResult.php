<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAcademicResult extends Model
{
    use HasFactory;

    protected $table = 'student_academic_results';

    protected $fillable = [
        'student_id',
        'class_id',
        'school_year_id',
        'overall_average',
        'conduct',
        'classification',
    ];

    protected $casts = [
        'overall_average' => 'float',
    ];


    /*
    |--------------------------------------------------------------------------
    | HỌC SINH
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LỚP
    |--------------------------------------------------------------------------
    */

    public function schoolClass()
    {
        return $this->belongsTo(
            SchoolClass::class,
            'class_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NĂM HỌC
    |--------------------------------------------------------------------------
    */

    public function schoolYear()
    {
        return $this->belongsTo(
            SchoolYear::class,
            'school_year_id'
        );
    }
}