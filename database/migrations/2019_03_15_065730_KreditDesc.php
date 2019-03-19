<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KreditDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_worklist_kwaitings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kredit_id')->nullable();
            $table->integer('author_id')->nullable();
            $table->integer('kreditor_id')->nullable();
            $table->integer('payment')->nullable();
            $table->integer('sum')->nullable();
            $table->integer('date_in')->nullable();
            $table->integer('status_date')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('day_count')->nullable();
            $table->integer('date_action')->nullable();
            $table->integer('date_offer')->nullable();
            $table->string('product')->nullable();
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
        Schema::dropIfExists('crm_worklist_kwaitings');
    }
}
