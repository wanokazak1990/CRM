<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\oa_complect;

class company_data extends Model
{
    //
    protected $fillable = ['type','company_id','vin','model_id','complect_id','transmission_id','wheel_id','location_id','pricestart','pricefinish'];

    public function checkEmpty()
    {
    	foreach ($this->attributes as $key => $value) {
    		if($key!='type' && $key!='company_id')
    			if(!empty($value))
    				return 1;
    	}
    	return 0;
    }

    public function complect()
    {
        return $this->hasMany('App\oa_complect','model_id','model_id');
    }

    public function getComplect($mas= array())
    {
        $res = $this->complect;
        
        foreach ($res as $key => $complect) {
            if($complect->motor)
                $mas[$complect->id] = $complect->name.' '.$complect->motor->nameForSelect()['name'] ;
        }
        return $mas;
    }

    public function getCurrentModel()
    {
        return $this->hasOne('App\oa_model','id','model_id');
    }

    public function getCurrentComplect()
    {
        return $this->hasOne('App\oa_complect','id','complect_id');
    }

    public function getComplectName()
    {
        return $this->getCurrentComplect->name.' '.$this->getCurrentComplect->motor->getEasyName();
    }

    public function getCurrentTransmission()
    {
        return $this->hasOne('App\type_transmission','id','transmission_id');
    }

    public function getCurrentWheel()
    {
        return $this->hasOne('App\type_wheel','id','wheel_id');
    }
    public function getCurrentLocation()
    {
        return $this->hasOne('App\ava_loc','id','wheel_id');
    }
}
