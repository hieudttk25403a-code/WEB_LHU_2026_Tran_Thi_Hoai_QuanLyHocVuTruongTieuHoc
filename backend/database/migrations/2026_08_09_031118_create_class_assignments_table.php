<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('class_assignments', function (Blueprint $table) {
            $table->id();

            // Lớp học
            $table->foreignId('class_id')
                ->constrained('school_classes')
                ->cascadeOnDelete();

            // Giáo viên chủ nhiệm
            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            // Năm học
            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnDelete();

            // Thời gian đảm nhiệm
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Ghi chú khi thay đổi GVCN
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_assignments');
    }
};