<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassAssignment extends Model
{
    use HasFactory;

    protected $table = 'class_assignments';

    protected $fillable = [
        'class_id',
        'teacher_id',
        'school_year_id',
        'start_date',
        'end_date',
        'note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Lớp học
     */
    public function schoolClass()
    {
        return $this->belongsTo(
            SchoolClass::class,
            'class_id',
            'id'
        );
    }

    /**
     * Giáo viên
     */
    public function teacher()
    {
        return $this->belongsTo(
            Teacher::class,
            'teacher_id',
            'id'
        );
    }

    /**
     * Năm học
     */
    public function schoolYear()
    {
        return $this->belongsTo(
            SchoolYear::class,
            'school_year_id',
            'id'
        );
    }
}