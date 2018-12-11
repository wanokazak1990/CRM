<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CRM_traffic extends Model
{
    protected $fillable = [
    	'cliend_id',
    	'traffic_type_id',
    	'creation_date',
    	'manager_id',
    	'admin_id',
    	'desired_model',
    	'assigned_action_id',
    	'comment'
    ];

    protected $attributes = [
    	'cliend_id' => 0,
    	'traffic_type_id' => 0,
    	'creation_date' => 0,
    	'manager_id' => 0,
    	'admin_id' => 0,
    	'desired_model' => 0,
    	'assigned_action_id' => 0,
        'comment' => '0'
    ];
    
    public $timestamps = false;
}
