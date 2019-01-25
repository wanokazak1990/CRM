<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class _tab_traffic extends Model
{
    //
    static public $tab_index = 1;

    public function model()
    {
    	return $this->hasOne('App\oa_model','id','desired_model');
    }

    public function manager()
    {
    	return $this->hasOne('App\User','id','manager_id');
    }

    public function action()
    {
    	return $this->hasOne('App\crm_assigned_action','id','assigned_action_id');
    }

    public function trafic_type()
    {
    	return $this->hasOne('App\crm_traffic_type','id','traffic_type_id');
    }

    public function admin()
    {
    	return $this->hasOne('App\User','id','admin_id');
    }

    
}
