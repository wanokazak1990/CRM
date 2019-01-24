<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_worklist extends Model
{
    //
    protected $fillable = array('traffic_id','client_id','manager_id');

    public function manager()
    {
    	return $this->hasOne('App\user','id','manager_id');
    }
}
