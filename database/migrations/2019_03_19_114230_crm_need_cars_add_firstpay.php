<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrmNeedCarsAddFirstpay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_need_cars', function (Blueprint $table) {
            $table->integer('firstpay')->after('pay_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_need_cars', function (Blueprint $table) {
            $table->dropColumn('firstpay');
        });
    }
}
