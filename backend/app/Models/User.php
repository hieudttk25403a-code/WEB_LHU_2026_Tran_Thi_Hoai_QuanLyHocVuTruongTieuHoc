<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'teacher_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Các thông báo do User tạo.
     */
    public function announcements()
    {
        return $this->hasMany(
            Announcement::class,
            'created_by'
        );
    }

    /**
     * Tài khoản này thuộc về giáo viên nào.
     */
    public function teacher()
    {
        return $this->belongsTo(
            Teacher::class,
            'teacher_id'
        );
    }

    /**
     * Kiểm tra tài khoản Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Kiểm tra tài khoản Giáo viên.
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Kiểm tra tài khoản Ban giám hiệu.
     */
    public function isBgh(): bool
    {
        return $this->role === 'bgh';
    }
}