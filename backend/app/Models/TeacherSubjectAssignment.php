<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubjectAssignment extends Model
{
    use HasFactory;

    protected $table = 'teacher_subject_assignments';

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'class_id',
        'school_year_id',
        'day_of_week',
        'period',
        'start_date',
        'end_date',
        'note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function teacher()
    {
        return $this->belongsTo(
            Teacher::class,
            'teacher_id'
        );
    }

    public function subject()
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }

    public function schoolClass()
    {
        return $this->belongsTo(
            SchoolClass::class,
            'class_id'
        );
    }

    public function schoolYear()
    {
        return $this->belongsTo(
            SchoolYear::class,
            'school_year_id'
        );
    }
}