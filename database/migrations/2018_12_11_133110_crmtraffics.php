<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Crmtraffics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CRM_traffics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cliend_id'); // ИД клиента
            $table->integer('traffic_type_id'); // ИД тип трафика
            $table->integer('creation_date'); // Дата создания
            $table->integer('manager_id'); // ИД назначенного менеджера
            $table->integer('admin_id'); // ИД админа
            $table->integer('desired_model'); // ИД интересующей модели
            $table->integer('assigned_action_id'); // ИД назначенного действия
            $table->string('comment'); // Комментарий
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CRM_traffics');
    }
}
