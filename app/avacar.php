<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class avacar extends Model
{
    //
    public $timestamps = true;
    protected $fillable = ['delivery_type', 'creator_id', 'logist_marker', 'radio_code', 'order_number', 'swap', 'brand_id', 'model_id', 'complect_id', 'color_id', 'vin', 'location_id', 'status_id', 'year', 'dopprice', 'created_at', 'updated_at', 'prodaction', 'date_storage', 'date_preparation', 'receipt_number', 'receipt_date', 'technic', 'estimated_purchase', 'actual_purchase', 'shipping_discount', 'pts_datepay', 'pts_datereception', 'debited_date', 'commercial application'];
    
    /*protected $attributes = [
    	'dopprice' => 0,
    	'prodaction'=> 0
    ];*/

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

    public function color()
    {
        return $this->hasOne('App\oa_color','id','color_id');
    }

    public function getAuthor()
    {
        return $this->hasOne('App\user','id','creator_id');
    }

    public function getDateOrder()
    {
        return $this->hasOne('App\ava_date_order','car_id','id');
    }

    public function getDatePlanned()
    {
        return $this->hasOne('App\ava_date_planned','car_id','id');
    }

    public function getDateBuild()
    {
        return $this->hasOne('App\ava_date_build','car_id','id');
    }

    public function getDateReady()
    {
        return $this->hasOne('App\ava_date_ready','car_id','id');
    }

    public function getDateShip()
    {
        return $this->hasOne('App\ava_date_ship','car_id','id');
    }

    public function getTechnic()
    {
        return $this->hasOne('App\user','id','technic');
    }

}
