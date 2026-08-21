<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentHealth extends Model
{
    use HasFactory;

    protected $table = 'student_health';

    protected $fillable = [
        'student_id',
        'height',
        'weight',
        'blood_group',
        'allergy',
        'note',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}