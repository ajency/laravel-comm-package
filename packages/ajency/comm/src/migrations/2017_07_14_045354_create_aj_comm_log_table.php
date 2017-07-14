<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjCommLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aj_comm_log_table', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->longText('request');
            $table->longText('notification_data');
            $table->longText('response');
            $table->string('api',255);
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
        //
    }
}
