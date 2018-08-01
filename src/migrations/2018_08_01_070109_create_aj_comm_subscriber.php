<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjCommSubscriber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::dropIfExists('aj_comm_emails');
      Schema::dropIfExists('aj_comm_mobile_nos');
      Schema::dropIfExists('aj_comm_webpush_ids');
      Schema::create('aj_comm_subscriber', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('reference');
            $table->string('type',10);
            $table->json('details');
            $table->boolean('is_primary');
            $table->boolean('is_verified');
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
      Schema::dropIfExists('aj_comm_subscriber');
      Schema::create('aj_comm_emails', function (Blueprint $table) {
          $table->integer('ref_id')->nullable();
          $table->string('ref_type', 16)->nullable();
          $table->string('email', 255);
          $table->boolean('is_primary');
          $table->timestamps();
          $table->unique('email', 'email_unq');
      });
      Schema::create('aj_comm_mobile_nos', function (Blueprint $table) {
          $table->integer('ref_id')->nullable();
          $table->string('ref_type', 16)->nullable();
          $table->string('mobile_no', 25);
          $table->string('country_code', 3);
          $table->boolean('is_primary');
          $table->timestamps();
          $table->unique(['mobile_no','country_code'], 'mobiles_unq');
      });
      Schema::create('aj_comm_webpush_ids', function (Blueprint $table) {
          $table->integer('ref_id')->nullable();
          $table->string('ref_type', 16)->nullable();
          $table->string('provider', 16);
          $table->string('subscriber_id', 255);
          $table->timestamps();
          $table->unique(['provider', 'subscriber_id'], 'pushes_unq');
      });
    }
}
