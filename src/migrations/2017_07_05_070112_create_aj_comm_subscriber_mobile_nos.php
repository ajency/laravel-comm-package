<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjCommSubscriberMobileNos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aj_comm_mobile_nos', function (Blueprint $table) {
            $table->integer('ref_id')->nullable();
            $table->string('ref_type',16)->nullable();
            $table->string('mobile_no', 25);
            $table->string('country_code', 3);
            $table->boolean('is_primary');
            $table->timestamps();
            $table->unique(['mobile_no','country_code'], 'mobiles_unq');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aj_comm_subscriber_mobile_nos');
    }
}
