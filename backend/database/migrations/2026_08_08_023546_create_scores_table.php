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
    Schema::create('scores', function (Blueprint $table) {
        $table->id();

        $table->foreignId('student_id')
              ->constrained('students')
              ->cascadeOnDelete();

        $table->foreignId('subject_id')
              ->constrained('subjects')
              ->cascadeOnDelete();

        $table->foreignId('school_year_id')
              ->constrained('school_years')
              ->cascadeOnDelete();

        $table->decimal('oral_score', 4, 2)->nullable();

        $table->decimal('fifteen_minute_score', 4, 2)->nullable();

        $table->decimal('midterm_score', 4, 2)->nullable();

        $table->decimal('final_score', 4, 2)->nullable();

        $table->decimal('average_score', 4, 2)->nullable();

        $table->string('classification')->nullable();

        $table->timestamps();

        $table->unique([
            'student_id',
            'subject_id',
            'school_year_id'
        ]);
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
};
