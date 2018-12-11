<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class avacar extends Model
{
    //
    public $timestamps = true;
    protected $fillable = ['brand_id','model_id','complect_id','color_id','vin','location_id','status_id','year','dopprice','prodaction'];
    protected $attributes = [
    	'dopprice' => 0,
    	'prodaction'=> 0
    ];

    public function getYearArray()
    {
    	$mas[date('Y')-1] = date('Y')-1;
    	$mas[date('Y')-0] = date('Y')-0;
    	$mas[date('Y')+1] = date('Y')+1;
    	return $mas;
    }

    public function brand()
    {
    	return $this->hasOne('App\oa_brand','id','brand_id');
    }

    public function model()
    {
    	return $this->hasOne('App\oa_model','id','model_id');
    }

    public function complect()
    {
    	return $this->hasOne('App\oa_complect','id','complect_id');
    }

    public function status()
    {
    	return $this->hasOne('App\ava_status','id','status_id');
    }

    public function location()
    {
    	return $this->hasOne('App\ava_loc','id','location_id');
    }

    public function packs()
    {
    	return $this->hasMany('App\ava_pack','avacar_id','id');
    }

    public function dops()
    {
    	return $this->hasMany('App\ava_dop','avacar_id','id');
    }

    public function packPrice($price=0)
    {
    	if(isset($this->packs))
    	{
    		foreach ($this->packs as $key => $pack) 
    		{	
    			if(isset($pack->pack))
    			  	$price += $pack->pack->price;
    		}
    	}
    	return $price;
    }				
    public function totalPrice($price = 0)
    {
    	$price += $this->dopprice;
    	if(isset($this->complect))
    		$price+=$this->complect->price;
    	$price+=$this->packPrice();
    	return $price;
    }
}
