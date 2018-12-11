<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyStart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('day_in')->default('0');
            $table->integer('day_out')->default('0');
            $table->integer('status')->default('0');
            $table->integer('razdel')->default('0');
            $table->string('name')->default('0');
            $table->integer('scenario')->default('0');
            $table->boolean('base')->default('0');
            $table->boolean('option')->default('0');
            $table->boolean('dop')->default('0');
            $table->integer('value')->default('0');
            $table->integer('max')->default('0');
            $table->integer('valute')->default('0');
            $table->integer('bydget')->default('0');
            $table->string('file')->default('0');
            $table->text('title');
            $table->text('ofer');
            $table->text('text');
            $table->boolean('main')->default('0');
            $table->boolean('immortal')->default('0');
            $table->boolean('timer')->default('0');
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
        Schema::dropIfExists('companys');
    }
}
