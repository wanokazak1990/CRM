<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class oa_option extends Model
{
    //
    protected $fillable = ['name','parent_id','filtered','filter_order'];
    public $timestamps = false;
    protected $attributes = [
        'name' => null,
        'parent_id' => 0,
        'filtered' => 0,
        'filter_order' => 0
    ];

    public function brands()
    {
    	return $this->hasMany('App\option_brand','option_id','id');
    }

    public function type()
    {
    	return $this->belongsTo('App\option_parent','parent_id','id');
    }
}
