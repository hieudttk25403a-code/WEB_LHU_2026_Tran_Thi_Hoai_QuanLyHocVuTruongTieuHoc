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
        | Chỉ thêm subject_id nếu bảng scores chưa có
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('scores', 'subject_id')) {

            Schema::table('scores', function (Blueprint $table) {

                $table->unsignedBigInteger('subject_id')
                    ->nullable()
                    ->after('student_id');

            });
        }
    }

    public function down()
    {
        /*
        |--------------------------------------------------------------------------
        | Chỉ xóa nếu subject_id tồn tại
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('scores', 'subject_id')) {

            Schema::table('scores', function (Blueprint $table) {

                $table->dropColumn('subject_id');

            });
        }
    }
};