<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_traffic extends Model
{
    protected $fillable = [
    	'client_id',
    	'traffic_type_id',
    	'creation_date',
    	'manager_id',
    	'admin_id',
    	'desired_model',
    	'assigned_action_id',
    	'comment'
    ];

    protected $attributes = [
    	'client_id' => 0,
    	'traffic_type_id' => 0,
    	'creation_date' => 0,
    	'manager_id' => 0,
    	'admin_id' => 0,
    	'desired_model' => 0,
    	'assigned_action_id' => 0,
        'comment' => '0'
    ];
    
    public $timestamps = false;

    public function traffic_type()
    {
        return $this->hasOne('App\crm_traffic_type','id','traffic_type_id');
    }

    public function client()
    {
        return $this->hasOne('App\crm_client','id','client_id');
    }

    public function manager()
    {
        return $this->hasOne('App\User','id','manager_id');
    }

    public function admin()
    {
        return $this->hasOne('App\User','id','admin_id');
    }

    public function model()
    {
        return $this->hasOne('App\oa_model','id','desired_model');
    }

    public function assigned_action()
    {
        return $this->hasOne('App\crm_assigned_action','id','assigned_action_id');
    }
}
