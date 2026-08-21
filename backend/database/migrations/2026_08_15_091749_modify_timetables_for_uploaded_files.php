<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('timetables', function (Blueprint $table) {

            $table->foreignId('timetable_upload_id')
                ->nullable()
                ->after('school_year_id')
                ->constrained('timetable_uploads')
                ->nullOnDelete();

            $table->integer('period')
                ->nullable()
                ->after('day_of_week');

            $table->string('subject_name')
                ->nullable()
                ->after('subject_id');

            $table->string('teacher_name')
                ->nullable()
                ->after('teacher_id');

            $table->string('day_of_week')
                ->nullable()
                ->change();

            $table->time('start_time')
                ->nullable()
                ->change();

            $table->time('end_time')
                ->nullable()
                ->change();

            $table->foreignId('subject_id')
                ->nullable()
                ->change();

            $table->foreignId('teacher_id')
                ->nullable()
                ->change();

            $table->string('room')
                ->nullable()
                ->change();
        });
    }

    public function down()
    {
        Schema::table('timetables', function (Blueprint $table) {

            $table->dropForeign([
                'timetable_upload_id'
            ]);

            $table->dropColumn([
                'timetable_upload_id',
                'period',
                'subject_name',
                'teacher_name'
            ]);
        });
    }
};