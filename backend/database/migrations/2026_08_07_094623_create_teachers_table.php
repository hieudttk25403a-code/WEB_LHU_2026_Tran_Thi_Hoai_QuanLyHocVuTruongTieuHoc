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
    Schema::create('teachers', function (Blueprint $table) {

        $table->id();

        $table->string('teacher_code')->unique();

        $table->string('full_name');

        $table->string('specialization');

        $table->string('department')->nullable();

        $table->string('phone')->nullable();

        $table->string('email')->nullable();

        $table->string('avatar')->nullable();

        $table->string('status')->default('Đang công tác');

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
        Schema::dropIfExists('teachers');
    }
};
