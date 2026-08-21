<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('score_edit_histories', function (Blueprint $table) {

            $table->id();

            // Điểm nào được sửa
            $table->foreignId('score_id')
                ->constrained('scores')
                ->cascadeOnDelete();

            // Tài khoản thực hiện sửa
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Cột điểm được sửa
            $table->string('score_type');

            // Điểm trước khi sửa
            $table->decimal('old_value', 4, 2)
                ->nullable();

            // Điểm sau khi sửa
            $table->decimal('new_value', 4, 2)
                ->nullable();

            // Ghi chú
            $table->text('note')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(
            'score_edit_histories'
        );
    }
};