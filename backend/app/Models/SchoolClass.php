<?php

namespace App\Models;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'school_classes';

    protected $fillable = [
        'class_name',
        'grade',
        'homeroom_teacher',
        'student_count',
        'status',
    ];

    /**
     * Danh sách học sinh của lớp
     */
    public function students()
    {
        return $this->hasMany(
            Student::class,
            'class_id',
            'id'
        );
    }

    /**
     * Lịch sử phân công giáo viên chủ nhiệm
     *
     * Bảng: class_assignments
     */
    public function classAssignments()
    {
        return $this->hasMany(
            ClassAssignment::class,
            'class_id',
            'id'
        )->orderByDesc('start_date');
    }

    /**
     * Alias cho code cũ trong Blade
     *
     * Một số view đang dùng:
     * $class->assignment_history
     *
     * nên giữ quan hệ này để không phát sinh lỗi.
     */
    public function assignment_history()
    {
        return $this->hasMany(
            ClassAssignment::class,
            'class_id',
            'id'
        )->orderByDesc('start_date');
    }

    /**
     * Phân công giáo viên bộ môn
     *
     * Bảng: teacher_subject_assignments
     */
    public function teacherSubjectAssignments()
    {
        return $this->hasMany(
            TeacherSubjectAssignment::class,
            'class_id',
            'id'
        );
    }

    /**
     * Lấy phân công GVCN hiện tại
     */
    public function currentClassAssignment()
    {
        return $this->hasOne(
            ClassAssignment::class,
            'class_id',
            'id'
        )
            ->whereNull('end_date')
            ->latest('start_date');
    }

    /**
     * Giáo viên chủ nhiệm hiện tại
     */
    public function currentHomeroomTeacher()
    {
        return $this->hasOneThrough(
            Teacher::class,
            ClassAssignment::class,
            'class_id',
            'id',
            'id',
            'teacher_id'
        )
            ->whereNull('class_assignments.end_date')
            ->latest('class_assignments.start_date');
    }

        public function attendances()
    {
        return $this->hasMany(
            Attendance::class,
            'class_id'
        );
    }
}