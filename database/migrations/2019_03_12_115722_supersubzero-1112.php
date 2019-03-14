<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Supersubzero1112 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_client_pays', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('worklist_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('pay')->nullable();
            $table->integer('date')->nullable();
            $table->integer('debt')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('crm_client_pays');
    }
}
