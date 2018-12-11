<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class oa_dop extends Model
{
    //
    protected $fillable = ['name','parent_id'];
    public $timestamps = false;

    public function type()
    {
    	return $this->hasOne('App\option_parent','id','parent_id');
    }
}
