<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class option_brand extends Model
{
    //
    protected $fillable = ['brand_id','option_id'];
    public $timestamps = false;

    public function getBrandObj()
    {
    	return $this->belongsTo('App\oa_brand','brand_id','id');
    }
}
