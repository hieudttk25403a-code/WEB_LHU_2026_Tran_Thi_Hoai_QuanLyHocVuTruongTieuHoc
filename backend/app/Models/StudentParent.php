<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentParent extends Model
{
    use HasFactory;

    protected $table = 'student_parents';

    protected $fillable = [
        'student_id',
        'full_name',
        'relationship',
        'occupation',
        'phone',
        'email',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}