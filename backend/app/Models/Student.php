<?php

namespace App\Models;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_code',
        'full_name',
        'date_of_birth',
        'gender',
        'address',
        'email',
        'phone',
        'class_id',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Lớp hiện tại
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Toàn bộ lịch sử lớp
     */
    public function classHistories()
    {
        return $this->hasMany(
            StudentClassHistory::class,
            'student_id'
        )->with([
            'schoolClass',
            'schoolYear'
        ])->orderByDesc('school_year_id');
    }

    /**
     * Phụ huynh
     */
    public function parents()
    {
        return $this->hasMany(StudentParent::class);
    }

    /**
     * Hồ sơ sức khỏe
     */
    public function healthProfile()
    {
        return $this->hasOne(StudentHealth::class);
    }

    /**
     * Điểm
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

public function academicResults()
{
    return $this->hasMany(
        StudentAcademicResult::class,
        'student_id'
    );
}

public function attendances()
{
    return $this->hasMany(
        Attendance::class,
        'student_id'
    );
}

public function yearResults()
{
    return $this->hasMany(
        StudentYearResult::class,
        'student_id'
    );
}

}