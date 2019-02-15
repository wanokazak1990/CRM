<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class complect_option extends Model
{
    //
    protected $fillable = ['option_id','complect_id'];
    public $timestamps = false;

    public function option()
	{
		return $this->hasOne('App\oa_option','id','option_id');
	}
}
