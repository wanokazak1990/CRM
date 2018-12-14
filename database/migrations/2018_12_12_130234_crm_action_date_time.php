<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrmActionDateTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_traffics', function (Blueprint $table) {
            $table->integer('action_date')->after('assigned_action_id');
            $table->integer('action_time')->after('action_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_traffics', function (Blueprint $table) {
            $table->dropColumn('action_date');
            $table->dropColumn('action_time');
        });
    }
}
