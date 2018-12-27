<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrmSettingsActiveAndType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_settings', function (Blueprint $table) {
            $table->string('field')->after('level');
            $table->integer('active')->after('field');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_settings', function (Blueprint $table) {
            $table->dropColumn('field');
            $table->dropColumn('active');
        });
    }
}
