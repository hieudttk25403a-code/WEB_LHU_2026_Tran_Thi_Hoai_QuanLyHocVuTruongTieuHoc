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
    Schema::create('school_classes', function (Blueprint $table) {

        $table->id();

        $table->string('class_name');

        $table->string('grade');

        $table->string('homeroom_teacher')->nullable();

        $table->integer('student_count')->default(0);

        $table->string('status')->default('Đang hoạt động');

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
        Schema::dropIfExists('school_classes');
    }
};
