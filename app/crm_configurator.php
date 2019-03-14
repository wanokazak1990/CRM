<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_configurator extends Model
{
    public $timestamps = false;

    public function model()
    {
    	return $this->hasOne('App\oa_model','id','model_id');
    }

    public function complect()
    {
    	return $this->hasOne('App\oa_complect','id','complect_id');
    }

    public function color()
    {
        return $this->hasOne('App\oa_color','id','color_id');
    }
}
