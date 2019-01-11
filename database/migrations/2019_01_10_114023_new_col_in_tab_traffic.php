<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewColInTabTraffic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            CREATE OR REPLACE VIEW _tab_traffics 
            AS SELECT 
                traffic.id,
                traffic.creation_date,
                traffic.traffic_type_id,
                traffic.desired_model,
                client.name,
                /*client.lastname*/
                client.address,
                traffic.comment,
                traffic.manager_id,
                traffic.admin_id,
                traffic.assigned_action_id,
                traffic.action_date,
                traffic.action_time
            FROM crm_traffics as traffic
            JOIN crm_clients as client
                on client.id = traffic.client_id
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
        DB::connection()->getPdo()->exec("DROP VIEW _tab_traffics");
    }
}
