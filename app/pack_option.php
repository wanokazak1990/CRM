<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pack_option extends Model
{
    //
    protected $fillable = ['option_id','pack_id'];
    public $timestamps = false;

    public function option()
    {
    	return $this->hasOne('App\oa_option','id','option_id');
    }
}
