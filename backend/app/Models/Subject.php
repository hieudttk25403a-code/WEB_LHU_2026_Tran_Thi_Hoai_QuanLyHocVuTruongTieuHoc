<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_code',
        'subject_name',
        'teacher',
        'grade',
        'status',
    ];

    public function assignments()
    {
        return $this->hasMany(
            TeacherSubjectAssignment::class,
            'subject_id'
        );
    }

    public function teachers()
    {
        return $this->belongsToMany(
            Teacher::class,
            'teacher_subject_assignments',
            'subject_id',
            'teacher_id'
        )
        ->withPivot([
            'class_id',
            'school_year_id',
            'day_of_week',
            'period',
            'start_date',
            'end_date',
        ]);
    }
}