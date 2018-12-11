<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class oa_complect extends Model
{
    //
    protected $fillable = ['name','price','brand_id','code','model_id','parent','sort','status','motor_id'];
    public $timestamps = false;
    protected $attributes = [
    	'name'=>null,
    	'price'=>0,
    	'brand_id'=>0,
    	'code'=>null,
    	'model_id'=>0,
    	'parent'=>0,
    	'sort'=>0,
    	'status'=>0,
    	'motor_id'=>null
    ];

    public function brand()
    {
    	return $this->hasOne('App\oa_brand','id','brand_id');
    }

    public function model()
    {
    	return $this->hasOne('App\oa_model','id','model_id');
    }

    public function motor()
    {
    	return $this->hasOne('App\oa_motor','id','motor_id');
    }
}
