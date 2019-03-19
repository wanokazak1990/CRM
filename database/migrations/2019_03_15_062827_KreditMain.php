<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KreditMain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_worklist_kredits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('worklist_id')->nullable();
            $table->integer('type_id')->nullable();
            $table->integer('payment')->nullable();
            $table->integer('valid_date')->nullable();
            $table->integer('margin_kredit')->nullable();
            $table->integer('margin_product')->nullable();
            $table->integer('margin_other')->nullable();
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
        Schema::dropIfExists('crm_worklist_kredits');
    }
}
