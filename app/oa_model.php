<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class oa_model extends Model
{
    //
    protected $fillable = array(
    	'brand_id','name','link','banner','icon','alpha','slogan','text','type_id','country_id','label','sort','status'
    );
    public $timestamps = false;

    public function brand()
	{	
		return $this->belongsTo('App\oa_brand');
	}

	public function type()
	{	
		return $this->belongsTo('App\type_model');
	}

	public function country()
	{	
		return $this->belongsTo('App\country_model');
	}

	public function colorBybrand()
	{
		return $this->hasMany('App\oa_color','brand_id','brand_id');
	}

	public function colorBymodel()
	{
		return $this->hasMany('App\model_color','model_id','id');
	}

	public function modelCount()
	{
		return avacar::where('model_id',$this->id)->count();
	}
}
