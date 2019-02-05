<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CarPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            CREATE OR REPLACE VIEW _view_car_price as SELECT 
                ava.id,
                IFNULL(complect.price,0) as complectprice,
                IFNULL(ava.dopprice,0) as dopprice,
                IFNULL(sum(pack.price),0) as packprice,
                IFNULL(complect.price,0)+IFNULL(ava.dopprice,0)+IFNULL(sum(pack.price),0) as totalprice
            FROM avacars AS ava 
            LEFT JOIN ava_packs AS ap ON ap.avacar_id = ava.id 
            LEFT JOIN packs AS pack ON pack.id = ap.pack_id
            LEFT JOIN oa_complects AS complect ON ava.complect_id = complect.id 
            GROUP BY ava.id
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
       DB::connection()->getPdo()->exec("DROP VIEW _view_car_price");
    }
}
