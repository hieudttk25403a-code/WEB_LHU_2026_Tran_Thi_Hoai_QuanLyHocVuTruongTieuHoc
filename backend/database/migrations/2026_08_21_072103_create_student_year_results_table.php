<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_year_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnDelete();

            // Hạnh kiểm do GVCN nhập
            $table->string('conduct')->nullable();

            // Trung bình tất cả các môn có điểm
            $table->decimal('academic_average', 4, 2)->nullable();

            // Danh hiệu
            $table->string('title')->nullable();

            $table->timestamps();

            $table->unique([
                'student_id',
                'school_year_id'
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_year_results');
    }
};