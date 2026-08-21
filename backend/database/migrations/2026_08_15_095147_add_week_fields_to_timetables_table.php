<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('timetables', function (Blueprint $table) {

            // Thêm tuần nếu chưa có
            if (!Schema::hasColumn('timetables', 'week')) {
                $table->unsignedInteger('week')
                    ->nullable()
                    ->after('school_year_id');
            }

            // Thêm ngày bắt đầu tuần nếu chưa có
            if (!Schema::hasColumn('timetables', 'week_start')) {
                $table->date('week_start')
                    ->nullable()
                    ->after('week');
            }

            // Thêm ngày kết thúc tuần nếu chưa có
            if (!Schema::hasColumn('timetables', 'week_end')) {
                $table->date('week_end')
                    ->nullable()
                    ->after('week_start');
            }

            // period đã tồn tại trong database nên không thêm lại
            // if (!Schema::hasColumn('timetables', 'period')) {
            //     $table->unsignedTinyInteger('period')
            //         ->nullable()
            //         ->after('day_of_week');
            // }

            // Thêm đường dẫn file nếu chưa có
            if (!Schema::hasColumn('timetables', 'file_path')) {
                $table->string('file_path')
                    ->nullable()
                    ->after('room');
            }

            // Thêm tên file nếu chưa có
            if (!Schema::hasColumn('timetables', 'file_name')) {
                $table->string('file_name')
                    ->nullable()
                    ->after('file_path');
            }
        });
    }

    public function down()
    {
        Schema::table('timetables', function (Blueprint $table) {

            $columns = [];

            if (Schema::hasColumn('timetables', 'week')) {
                $columns[] = 'week';
            }

            if (Schema::hasColumn('timetables', 'week_start')) {
                $columns[] = 'week_start';
            }

            if (Schema::hasColumn('timetables', 'week_end')) {
                $columns[] = 'week_end';
            }

            if (Schema::hasColumn('timetables', 'file_path')) {
                $columns[] = 'file_path';
            }

            if (Schema::hasColumn('timetables', 'file_name')) {
                $columns[] = 'file_name';
            }

            // Không xóa period vì nó đã tồn tại từ migration khác
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};