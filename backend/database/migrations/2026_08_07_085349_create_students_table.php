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
        Schema::create('students', function (Blueprint $table) {

            $table->id();

            // Mã học sinh
            $table->string('student_code')->unique();

            // Họ và tên
            $table->string('full_name');

            // Ngày sinh
            $table->date('date_of_birth');

            // Giới tính
            $table->enum('gender', ['Nam', 'Nữ']);

            // Địa chỉ
            $table->text('address')->nullable();

            // Email
            $table->string('email')->nullable();

            // Số điện thoại
            $table->string('phone')->nullable();

            // Lớp học (sẽ liên kết sau)
            $table->unsignedBigInteger('class_id')->nullable();

            // Trạng thái
            $table->string('status')->default('Đang học');

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
        Schema::dropIfExists('students');
    }
};