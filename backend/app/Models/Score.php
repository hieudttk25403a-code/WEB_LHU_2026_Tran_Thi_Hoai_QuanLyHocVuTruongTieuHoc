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
        'teacher_id',
        'oral_score',
        'fifteen_minute_score',
        'midterm_score',
        'final_score',
        'average_score',
        'note',
    ];

    protected $casts = [
        'oral_score' => 'float',
        'fifteen_minute_score' => 'float',
        'midterm_score' => 'float',
        'final_score' => 'float',
        'average_score' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | QUAN HỆ HỌC SINH
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUAN HỆ MÔN HỌC
    |--------------------------------------------------------------------------
    */

    public function subject()
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUAN HỆ NĂM HỌC
    |--------------------------------------------------------------------------
    */

    public function schoolYear()
    {
        return $this->belongsTo(
            SchoolYear::class,
            'school_year_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUAN HỆ GIÁO VIÊN
    |--------------------------------------------------------------------------
    */

    public function teacher()
    {
        return $this->belongsTo(
            Teacher::class,
            'teacher_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LỊCH SỬ CHỈNH SỬA
    |--------------------------------------------------------------------------
    */

    public function editHistories()
    {
        return $this->hasMany(
            ScoreEditHistory::class,
            'score_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TÍNH ĐIỂM TRUNG BÌNH
    |--------------------------------------------------------------------------
    |
    | Chỉ tính TB khi đủ cả 4 loại điểm:
    |
    | - Miệng
    | - 15 phút
    | - Giữa kỳ
    | - Cuối kỳ
    |
    */

    public function calculateAverage()
    {
        if (
            $this->oral_score === null ||
            $this->fifteen_minute_score === null ||
            $this->midterm_score === null ||
            $this->final_score === null
        ) {
            return null;
        }

        return round(
            (
                $this->oral_score
                + $this->fifteen_minute_score
                + $this->midterm_score
                + $this->final_score
            ) / 4,
            2
        );
    }

    /*
    |--------------------------------------------------------------------------
    | XẾP LOẠI MÔN
    |--------------------------------------------------------------------------
    */

    public function getClassificationAttribute()
    {
        if ($this->average_score === null) {
            return 'Chưa có điểm';
        }

        if ($this->average_score >= 8) {
            return 'Tốt';
        }

        if ($this->average_score >= 6.5) {
            return 'Khá';
        }

        if ($this->average_score >= 5) {
            return 'Đạt';
        }

        return 'Chưa đạt';
    }

    /*
    |--------------------------------------------------------------------------
    | TỔNG SỐ LẦN GIÁO VIÊN ĐÃ SỬA
    |--------------------------------------------------------------------------
    |
    | QUAN TRỌNG:
    |
    | Không tính theo từng cột.
    |
    | Nếu giáo viên:
    |
    | Lần 1: sửa Miệng
    | Lần 2: sửa 15 phút + GK
    | Lần 3: sửa CK
    |
    | => Tổng cộng 3 lần.
    |
    */

    public function totalEditCount(): int
    {
        return $this->editHistories()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | GIÁO VIÊN CÒN ĐƯỢC SỬA KHÔNG
    |--------------------------------------------------------------------------
    */

    public function canTeacherEdit(): bool
    {
        return $this->totalEditCount() < 3;
    }

    /*
    |--------------------------------------------------------------------------
    | ĐÃ KHÓA CHƯA
    |--------------------------------------------------------------------------
    */

    public function isEditLocked(): bool
    {
        return $this->totalEditCount() >= 3;
    }

    /*
    |--------------------------------------------------------------------------
    | SỐ LẦN CÒN LẠI
    |--------------------------------------------------------------------------
    */

    public function remainingEdits(): int
    {
        return max(
            0,
            3 - $this->totalEditCount()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TÊN HIỂN THỊ CỘT ĐIỂM
    |--------------------------------------------------------------------------
    */

    public function scoreTypeName(string $field): string
    {
        return match ($field) {

            'oral_score' =>
                'Điểm miệng',

            'fifteen_minute_score' =>
                'Điểm 15 phút',

            'midterm_score' =>
                'Điểm giữa kỳ',

            'final_score' =>
                'Điểm cuối kỳ',

            default =>
                'Điểm',
        };
    }
}