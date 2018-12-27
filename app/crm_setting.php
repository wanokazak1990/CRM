<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_setting extends Model
{
    protected $fillable = [
    	'name',
    	'level',
    	'field',
    	'active'
    ];

    protected $attributes = [
    	'name' => '0',
    	'level' => 0,
    	'field' => '0',
    	'active' => 0
    ];
    
    public $timestamps = false;
}
