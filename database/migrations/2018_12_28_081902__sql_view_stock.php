<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SqlViewStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            CREATE OR REPLACE VIEW _tab_stocks 
            AS SELECT 
                car.id
            FROM avacars as car
        ";

        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec("DROP VIEW _tab_stocks");
    }
}
