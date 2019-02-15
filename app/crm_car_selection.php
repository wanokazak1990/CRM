<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_car_selection extends Model
{
    public $timestamps = false;

    public function avacar()
    {
    	return $this->hasOne('App\avacar','id','car_id');
    }
}
