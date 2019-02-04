<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_testdrive extends Model
{
    public $timestamps = false;

    public function model()
    {
    	return $this->hasOne('App\oa_model','id','model_id');
    }
}
