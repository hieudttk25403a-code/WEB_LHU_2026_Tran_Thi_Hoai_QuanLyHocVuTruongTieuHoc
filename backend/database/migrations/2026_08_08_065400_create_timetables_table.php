<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('timetables', function (Blueprint $table) {

        $table->id();

        $table->foreignId('class_id')
              ->constrained('school_classes')
              ->onDelete('cascade');

        $table->foreignId('subject_id')
              ->constrained('subjects')
              ->onDelete('cascade');

        $table->foreignId('teacher_id')
              ->constrained('teachers')
              ->onDelete('cascade');

        $table->foreignId('school_year_id')
              ->constrained('school_years')
              ->onDelete('cascade');

        $table->string('day_of_week');

        $table->time('start_time');

        $table->time('end_time');

        $table->string('room')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timetables');
    }
};
