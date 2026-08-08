<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [

        'class_name',

        'grade',

        'homeroom_teacher',

        'student_count',

        'status'

    ];
}