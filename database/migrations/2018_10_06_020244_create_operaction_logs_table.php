<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperactionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operaction_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip');
            $table->text('input');
            $table->index('user_id');
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
        Schema::dropIfExists('operaction_logs');
    }
}
