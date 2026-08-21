<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        /*
        |--------------------------------------------------------------------------
        | Giáo viên
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('teachers', 'gender')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->string('gender', 20)
                    ->nullable()
                    ->after('full_name');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Thời khóa biểu
        |--------------------------------------------------------------------------
        */

        Schema::table('timetables', function (Blueprint $table) {
            if (!Schema::hasColumn('timetables', 'teacher_subject_assignment_id')) {
                $table->unsignedBigInteger(
                    'teacher_subject_assignment_id'
                )->nullable()->after('id');

                $table->index(
                    'teacher_subject_assignment_id',
                    'timetable_assignment_idx'
                );
            }

            if (!Schema::hasColumn('timetables', 'start_date')) {
                $table->date('start_date')
                    ->nullable()
                    ->after('room');
            }

            if (!Schema::hasColumn('timetables', 'end_date')) {
                $table->date('end_date')
                    ->nullable()
                    ->after('start_date');
            }
        });
    }

    public function down()
    {
        if (Schema::hasColumn('teachers', 'gender')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropColumn('gender');
            });
        }

        Schema::table('timetables', function (Blueprint $table) {
            if (Schema::hasColumn(
                'timetables',
                'teacher_subject_assignment_id'
            )) {
                $table->dropIndex('timetable_assignment_idx');
                $table->dropColumn(
                    'teacher_subject_assignment_id'
                );
            }

            if (Schema::hasColumn('timetables', 'start_date')) {
                $table->dropColumn('start_date');
            }

            if (Schema::hasColumn('timetables', 'end_date')) {
                $table->dropColumn('end_date');
            }
        });
    }
};