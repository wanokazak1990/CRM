<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrmConfigurators extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_configurators', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('worklist_id');
            $table->integer('model_id');
            $table->integer('complect_id');
            $table->integer('color_id')->nullable();
            $table->string('options')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_configurators');
    }
}
