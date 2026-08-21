<?php

namespace App\Models;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_code',
        'full_name',
        'gender',
        'specialization',
        'department',
        'phone',
        'email',
        'avatar',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | TÀI KHOẢN ĐĂNG NHẬP
    |--------------------------------------------------------------------------
    |
    | Mỗi giáo viên có tối đa một tài khoản đăng nhập.
    |
    | Teacher
    |    ↓
    | User
    |
    */

    public function user()
    {
        return $this->hasOne(
            User::class,
            'teacher_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ĐIỂM
    |--------------------------------------------------------------------------
    */

    public function scores()
    {
        return $this->hasMany(
            Score::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PHÂN CÔNG GIÁO VIÊN - MÔN HỌC
    |--------------------------------------------------------------------------
    */

    public function subjectAssignments()
    {
        return $this->hasMany(
            TeacherSubjectAssignment::class,
            'teacher_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PHÂN CÔNG GIÁO VIÊN - LỚP
    |--------------------------------------------------------------------------
    */

    public function classAssignments()
    {
        return $this->hasMany(
            ClassAssignment::class,
            'teacher_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | XÁC ĐỊNH GIÁO VIÊN CHUYÊN
    |--------------------------------------------------------------------------
    */

    public function isEnglishSpecialist(): bool
    {
        return preg_match(
            '/^GVCA\d+$/i',
            trim($this->teacher_code)
        ) === 1;
    }

    public function isITSpecialist(): bool
    {
        $code = strtoupper(
            trim($this->teacher_code)
        );

        return preg_match(
            '/^GVTH\d+$/',
            $code
        ) === 1
            || preg_match(
                '/^GVCT\d+$/',
                $code
            ) === 1;
    }

    public function isSpecialist(): bool
    {
        return $this->isEnglishSpecialist()
            || $this->isITSpecialist();
    }

    public function specialistSubjectName(): ?string
    {
        if ($this->isEnglishSpecialist()) {
            return 'Ngoại ngữ 1';
        }

        if ($this->isITSpecialist()) {
            return 'Tin học và Công nghệ';
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | CHUYÊN MÔN TỰ ĐỘNG THEO MÃ
    |--------------------------------------------------------------------------
    */

    public function defaultSpecialization(): string
    {
        /*
        | Giáo viên chuyên Tiếng Anh
        */

        if ($this->isEnglishSpecialist()) {
            return 'Giáo viên chuyên Tiếng Anh';
        }

        /*
        | Giáo viên chuyên Tin học
        */

        if ($this->isITSpecialist()) {
            return 'Giáo viên chuyên Tin học';
        }

        /*
        | Giáo viên tiểu học thông thường
        */

        if (
            preg_match(
                '/^GV\d+$/i',
                trim($this->teacher_code)
            )
        ) {
            return 'Giáo viên tiểu học';
        }

        /*
        | Nếu không xác định được thì giữ chuyên môn hiện tại.
        */

        return $this->specialization
            ?: 'Giáo viên tiểu học';
    }

public function attendances()
{
    return $this->hasMany(
        Attendance::class,
        'teacher_id'
    );
}
}