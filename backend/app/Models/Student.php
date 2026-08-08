<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentParent;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_code',
        'full_name',
        'date_of_birth',
        'gender',
        'address',
        'email',
        'phone',
        'class_id',
        'status'
    ];

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function parents()
{
    return $this->hasMany(StudentParent::class);
}

    public function health()
{
    return $this->hasOne(StudentHealth::class);
}
}