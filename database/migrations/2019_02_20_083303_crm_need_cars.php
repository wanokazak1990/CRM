<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrmNeedCars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_need_cars', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('worklist_id')->nullable();
            $table->integer('model_id')->nullable();
            $table->integer('wheel_id')->nullable();
            $table->integer('transmission_id')->nullable();
            $table->integer('purchase_type')->nullable();
            $table->integer('pay_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_need_cars');
    }
}
