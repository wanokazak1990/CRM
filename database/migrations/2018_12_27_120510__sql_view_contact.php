<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

class SqlViewContact extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
            CREATE VIEW _tab_clients as SELECT 
                client.id,
                client.name,
                /*client.lastname,*/
                client.phone,
                client.email,
                traffic.desired_model,
                traffic.manager_id,
                traffic.assigned_action_id,
                traffic.action_date
            FROM crm_clients as client 
            JOIN crm_traffics as traffic 
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
        DB::connection()->getPdo()->exec("DROP VIEW _tab_clients");
    }
}
