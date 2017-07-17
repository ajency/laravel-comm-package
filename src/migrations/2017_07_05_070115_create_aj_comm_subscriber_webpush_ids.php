<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjCommSubscriberWebpushIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aj_comm_subscriber_webpush_ids', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('provider',16);
            $table->string('subscriber_id',255);
            $table->timestamps();
            $table->unique(['provider', 'subscriber_id'],'pushes_unq');
            $table->primary(['user_id', 'subscriber_id', 'provider'],'pushes_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aj_comm_subscriber_webpush_ids');
    }
}
