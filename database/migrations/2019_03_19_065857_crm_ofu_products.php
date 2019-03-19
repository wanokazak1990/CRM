<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrmOfuProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_ofu_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('worklist_id');
            $table->integer('author_id')->comment('консультант');
            $table->integer('product_id')->comment('продукт');
            $table->integer('partner_id')->comment('партнер')->nullable();
            $table->integer('price')->comment('стоимость продукта')->nullable();
            $table->integer('creation_date')->comment('дата оформления')->nullable();
            $table->integer('end_date')->comment('срок действия')->nullable();
            $table->integer('profit')->comment('кв за продукт')->nullable();
            $table->integer('profit_date')->comment('дата выплаты кв')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_ofu_products');
    }
}
