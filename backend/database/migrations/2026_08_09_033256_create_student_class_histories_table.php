<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_class_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('class_id')
                ->constrained('school_classes')
                ->cascadeOnDelete();

            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnDelete();

            $table->date('start_date')->nullable();

            $table->date('end_date')->nullable();

            $table->text('note')->nullable();

            $table->string('status')->default('Đang học');

            $table->timestamps();

            $table->index([
                'student_id',
                'school_year_id'
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_class_histories');
    }
};