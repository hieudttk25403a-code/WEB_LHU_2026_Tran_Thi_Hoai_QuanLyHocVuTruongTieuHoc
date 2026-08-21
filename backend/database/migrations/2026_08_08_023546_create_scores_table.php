<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();

            // Học sinh
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            // Môn học
            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            // Năm học
            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnDelete();

            // Giáo viên nhập điểm
            $table->foreignId('teacher_id')
                ->nullable()
                ->constrained('teachers')
                ->nullOnDelete();

            // Điểm
            $table->decimal('oral_score', 4, 2)->nullable();
            $table->decimal('fifteen_minute_score', 4, 2)->nullable();
            $table->decimal('midterm_score', 4, 2)->nullable();
            $table->decimal('final_score', 4, 2)->nullable();

            // Điểm trung bình
            $table->decimal('average_score', 4, 2)->nullable();

            // Ghi chú
            $table->text('note')->nullable();

            $table->timestamps();

            // Một học sinh - một môn - một năm học
            $table->unique([
                'student_id',
                'subject_id',
                'school_year_id'
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists('scores');
    }
};