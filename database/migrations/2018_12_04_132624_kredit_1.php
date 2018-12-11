<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Kredit1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kredits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->default(0);
            $table->string('name')->default(null);
            $table->string('banner')->default(null);
            $table->string('rate')->default(null);
            $table->string('pay')->default(null);
            $table->string('period')->default(null);
            $table->string('contibution')->default(null);
            $table->string('disklamer')->default(null);
            $table->string('dopoption')->default(null);
            $table->integer('day_in')->default(0);
            $table->integer('day_out')->default(0);
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
        Schema::dropIfExists('kredits');
    }
}
