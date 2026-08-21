<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function studentClassHistories()
    {
        return $this->hasMany(
            StudentClassHistory::class,
            'school_year_id'
        );
    }

    public function scores()
    {
        return $this->hasMany(
            Score::class,
            'school_year_id'
        );
    }

    public function classAssignments()
    {
        return $this->hasMany(
            ClassAssignment::class,
            'school_year_id'
        );
    }

public function teacherSubjectAssignments()
{
    return $this->hasMany(
        TeacherSubjectAssignment::class,
        'school_year_id'
    );
}
}