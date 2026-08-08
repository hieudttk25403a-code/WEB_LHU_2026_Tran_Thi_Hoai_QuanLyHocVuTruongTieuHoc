<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'school_year_id',
        'oral_score',
        'fifteen_minute_score',
        'midterm_score',
        'final_score',
        'average_score',
        'classification',
    ];

    /**
     * Điểm thuộc về học sinh
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Điểm thuộc về môn học
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Điểm thuộc về năm học
     */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}