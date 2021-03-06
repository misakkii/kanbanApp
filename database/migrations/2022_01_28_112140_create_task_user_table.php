<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('ユーザーID');
            $table->foreignId('task_id')->constrained('tasks')->comment('プロジェクトID');
            $table->integer('order_num')->nullable();
            $table->string('status')->default('standby');
            $table->integer('total_work_minute')->nullable();
            $table->integer('total_hour')->default(0);
            $table->integer('total_minute')->default(0);
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('task_user');
    }
}
