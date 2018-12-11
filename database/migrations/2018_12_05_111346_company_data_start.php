<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyDataStart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->default('0');
            $table->string('vin')->default('null');
            $table->integer('model_id')->default('0');
            $table->integer('complect_id')->default('0');
            $table->integer('transmission_id')->default('0');
            $table->integer('privod_id')->default('0');
            $table->integer('location_id')->default('0');
            $table->integer('pricestart')->default('0');
            $table->integer('pricefinish')->default('0');
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
        Schema::dropIfExists('company_datas');
    }
}
