<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClassHistory extends Model
{
    use HasFactory;

    protected $table = 'student_class_histories';

    protected $fillable = [
        'student_id',
        'class_id',
        'school_year_id',
        'status',
        'note',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
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