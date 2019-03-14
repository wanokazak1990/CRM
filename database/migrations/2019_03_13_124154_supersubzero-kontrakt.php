<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SupersubzeroKontrakt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_worklist_contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('worklist_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('author_id')->nullable();
            $table->integer('number')->nullable();
            $table->integer('date')->nullable();
            $table->integer('date_crash')->nullable();
            $table->integer('status')->nullable();
            $table->integer('crash')->nullable();
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
        Schema::dropIfExists('crm_worklist_contracts');
    }
}
