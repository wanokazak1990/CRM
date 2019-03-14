<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrmCommercialOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_commercial_offers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('worklist_id');
            $table->integer('creation_date');
            $table->string('cars_ids')->comment('IDs существующих машин')->nullable();
            $table->string('cfg_cars')->comment('Автомобили из Конфигуратора')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_commercial_offers');
    }
}
