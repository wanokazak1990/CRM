<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ava_dop extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['dop_id','avacar_id'];

    public function dop()
    {
    	return $this->hasOne('App\oa_dop','id','dop_id');
    }
}
