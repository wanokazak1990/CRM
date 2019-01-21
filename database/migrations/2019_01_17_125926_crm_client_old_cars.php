<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrmClientOldCars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_client_old_cars', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->integer('odometer');
            $table->integer('owners');
            $table->integer('personal_rating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_client_old_cars');
    }
}
