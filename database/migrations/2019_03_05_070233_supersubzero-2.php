<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Supersubzero2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ava_data_provisions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('car_id')->nullable();
            $table->integer('count_days')->nullable();
            $table->integer('date_provision')->nullable();
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
        Schema::dropIfExists('ava_data_provisions');
    }
}
