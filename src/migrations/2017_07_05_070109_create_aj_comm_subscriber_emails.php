<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjCommSubscriberEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aj_comm_subscriber_emails', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('email',255);
            $table->boolean('is_primary');
            $table->timestamps();
            $table->primary(['user_id', 'email'],'emails_pk');
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aj_comm_subscriber_emails');
    }
}
