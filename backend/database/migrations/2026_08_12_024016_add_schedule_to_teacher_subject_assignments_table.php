<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teacher_subject_assignments', function (Blueprint $table) {

            $table->unsignedTinyInteger('day_of_week')
                ->nullable()
                ->after('school_year_id');

            $table->unsignedTinyInteger('period')
                ->nullable()
                ->after('day_of_week');

            $table->index(
                ['day_of_week', 'period'],
                'tsa_day_period_index'
            );
        });
    }

    public function down()
    {
        Schema::table('teacher_subject_assignments', function (Blueprint $table) {

            $table->dropIndex('tsa_day_period_index');

            $table->dropColumn([
                'day_of_week',
                'period',
            ]);
        });
    }
};