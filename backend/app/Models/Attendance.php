<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'student_id',
        'class_id',
        'teacher_id',
        'school_year_id',
        'attendance_date',
        'status',
        'note',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Học sinh
    |--------------------------------------------------------------------------
    */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Lớp
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
    | Giáo viên
    |--------------------------------------------------------------------------
    */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Năm học
    |--------------------------------------------------------------------------
    */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}