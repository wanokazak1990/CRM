<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrmPartners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_partners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('short_name')->comment('Короткое имя');
            $table->string('license');
            $table->integer('type')->comment('1 - Кредитор, 2 - Партнер, 3 - Кредитор и Партнер');
            $table->integer('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_partners');
    }
}
