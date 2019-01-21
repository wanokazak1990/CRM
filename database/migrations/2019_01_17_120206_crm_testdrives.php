<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrmTestdrives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_testdrives', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->integer('worklist_id');
            $table->integer('model_id');
            $table->integer('date');
            $table->integer('time');
            $table->integer('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_testdrives');
    }
}
