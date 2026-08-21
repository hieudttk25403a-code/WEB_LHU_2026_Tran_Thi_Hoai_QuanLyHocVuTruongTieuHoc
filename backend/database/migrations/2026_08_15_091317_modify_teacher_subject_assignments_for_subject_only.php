<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teacher_subject_assignments', function (Blueprint $table) {

            $table->string('day_of_week')
                ->nullable()
                ->change();

            $table->integer('period')
                ->nullable()
                ->change();

            $table->date('start_date')
                ->nullable()
                ->change();

            $table->date('end_date')
                ->nullable()
                ->change();
        });
    }

    public function down()
    {
        Schema::table('teacher_subject_assignments', function (Blueprint $table) {

            $table->string('day_of_week')
                ->nullable(false)
                ->change();

            $table->integer('period')
                ->nullable(false)
                ->change();

            $table->date('start_date')
                ->nullable(false)
                ->change();

            $table->date('end_date')
                ->nullable(false)
                ->change();
        });
    }
};