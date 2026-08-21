<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentYearResult extends Model
{
    use HasFactory;

    protected $table = 'student_year_results';

    protected $fillable = [
        'student_id',
        'school_year_id',
        'conduct',
        'academic_average',
        'title',
    ];

    protected $casts = [
        'academic_average' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}