<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pack extends Model
{
    //
    protected $fillable = ['name','code','price','type','brand_id'];
    public $timestamps = false;

    public function brand()
    {
    	return $this->belongsTo('App\oa_brand');
    }

    public function model()
    {
    	return $this->hasMany('App\model_pack','pack_id','id');
    }

    public function option()
    {
    	return $this->hasMany('App\pack_option','pack_id','id')->with('option');
    }

    public function fullName()
    {
    	return 'Опция: '.$this->name.' код-'.$this->code.' '.$this->price.'руб.';
    }
}
