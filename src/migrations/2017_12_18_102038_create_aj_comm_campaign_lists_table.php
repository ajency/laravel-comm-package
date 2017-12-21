<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAjCommCampaignListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aj_comm_campaign_lists', function (Blueprint $table) {

            $table->increments('id');
            $table->string('type', 250)->nullable();
            $table->string('list_name', 250)->nullable();
            $table->string('list_id', 250)->nullable();
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
        Schema::dropIfExists('aj_comm_campaign_lists');
    }
}
