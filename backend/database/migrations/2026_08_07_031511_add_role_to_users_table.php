<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Quyền của tài khoản:
            // admin   = Quản trị viên
            // teacher = Giáo viên
            // bgh     = Ban giám hiệu
            $table->enum('role', ['admin', 'teacher', 'bgh'])
                ->default('teacher')
                ->after('password');

            // Liên kết tài khoản với giáo viên
            // Tài khoản admin và bgh sẽ để NULL
            $table->unsignedBigInteger('teacher_id')
                ->nullable()
                ->after('role');

            $table->foreign('teacher_id')
                ->references('id')
                ->on('teachers')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['role', 'teacher_id']);
        });
    }
};