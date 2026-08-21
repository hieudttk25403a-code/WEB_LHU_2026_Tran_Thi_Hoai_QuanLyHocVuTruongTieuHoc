<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teacher_subject_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
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

            $table->timestamps();

$table->index(
    ['teacher_id', 'subject_id', 'class_id', 'school_year_id'],
    'tsa_teacher_subject_year_idx'
);
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_subject_assignments');
    }
};