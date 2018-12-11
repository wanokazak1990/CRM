<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class file extends Model
{
    protected $fillable = [
    	'type_id',
    	'name',
    	'file',
    	'model_id',
        'brand_id'
    ];

    protected $attributes = [
    	'type_id' => 0,
    	'name' => '0',
    	'file' => '0',
    	'model_id' => 0,
        'brand_id' => 0
    ];
    
    public $timestamps = false;

    public function type()
    {
        return $this->hasOne('App\file_type','id','type_id');
    }

    public function model()
    {
        return $this->hasOne('App\oa_model','id','model_id');
    }
}
