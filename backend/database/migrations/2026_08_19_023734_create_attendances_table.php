<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('school_year_id');

            $table->date('attendance_date');

            $table->enum('status', [
                'present',
                'absent',
                'late',
                'excused'
            ])->default('present');

            $table->string('note')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Mỗi học sinh chỉ có một bản ghi điểm danh trong một ngày
            |--------------------------------------------------------------------------
            */
            $table->unique([
                'student_id',
                'attendance_date'
            ]);

            /*
            |--------------------------------------------------------------------------
            | Foreign keys
            |--------------------------------------------------------------------------
            */
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');

            $table->foreign('class_id')
                ->references('id')
                ->on('school_classes')
                ->onDelete('cascade');

            $table->foreign('teacher_id')
                ->references('id')
                ->on('teachers')
                ->onDelete('cascade');

            $table->foreign('school_year_id')
                ->references('id')
                ->on('school_years')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};