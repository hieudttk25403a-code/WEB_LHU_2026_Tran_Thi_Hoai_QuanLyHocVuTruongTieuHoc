<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreEditHistory extends Model
{
    use HasFactory;

    protected $table = 'score_edit_histories';

    protected $fillable = [
        'score_id',
        'user_id',
        'score_type',
        'old_value',
        'new_value',
        'note',
    ];

    protected $casts = [
        'old_value' => 'decimal:2',
        'new_value' => 'decimal:2',
    ];

    /**
     * Điểm được chỉnh sửa
     */
    public function score()
    {
        return $this->belongsTo(
            Score::class,
            'score_id'
        );
    }

    /**
     * Tài khoản thực hiện chỉnh sửa
     */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /**
     * Tên hiển thị loại điểm
     */
    public function getScoreTypeLabelAttribute()
    {
        return match ($this->score_type) {

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