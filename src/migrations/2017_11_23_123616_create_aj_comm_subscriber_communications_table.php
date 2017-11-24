<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjCommSubscriberCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aj_comm_subscriber_communications', function (Blueprint $table) {
             
            $table->increments('id');
            $table->integer('ref_id')->nullable();
            $table->string('ref_type', 16)->nullable();
            $table->string('value', 25);
            $table->boolean('is_primary');
            $table->string('service', 255);
            $table->string('country_code', 3)->default(0);
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
        Schema::dropIfExists('aj_comm_subscriber_communications');
    }
}
