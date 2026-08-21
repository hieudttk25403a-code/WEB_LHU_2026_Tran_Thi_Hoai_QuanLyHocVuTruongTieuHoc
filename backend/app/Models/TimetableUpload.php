<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableUpload extends Model
{
    use HasFactory;

    protected $table = 'timetable_uploads';

    protected $fillable = [
        'class_id',
        'school_year_id',
        'week_number',
        'start_date',
        'end_date',
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
        'status',
        'processing_note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(
            SchoolClass::class,
            'class_id'
        );
    }

    public function schoolYear()
    {
        return $this->belongsTo(
            SchoolYear::class,
            'school_year_id'
        );
    }

    public function timetables()
    {
        return $this->hasMany(
            Timetable::class,
            'timetable_upload_id'
        );
    }
}