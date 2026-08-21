<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('timetable_uploads', function (Blueprint $table) {

            $table->id();

            $table->foreignId('class_id')
                ->constrained('school_classes')
                ->cascadeOnDelete();

            $table->foreignId('school_year_id')
                ->constrained('school_years')
                ->cascadeOnDelete();

            $table->integer('week_number');

            $table->date('start_date');

            $table->date('end_date');

            $table->string('file_name');

            $table->string('file_path');

            $table->string('file_type')->nullable();

            $table->string('mime_type')->nullable();

            $table->enum('status', [
                'uploaded',
                'processed',
                'processing',
                'failed'
            ])->default('uploaded');

            $table->text('processing_note')->nullable();

            $table->timestamps();

            $table->index([
                'class_id',
                'school_year_id',
                'week_number'
            ]);
        });
    }

    public function down()
    {
        Schema::dropIfExists('timetable_uploads');
    }
};