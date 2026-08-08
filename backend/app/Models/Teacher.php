<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [

        'teacher_code',

        'full_name',

        'specialization',

        'department',

        'phone',

        'email',

        'avatar',

        'status'

    ];
}