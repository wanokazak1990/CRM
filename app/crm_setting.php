<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_setting extends Model
{
    protected $fillable = [
    	'name',
    	'level'
    ];

    protected $attributes = [
    	'name' => '0',
    	'level' => 0
    ];
    
    public $timestamps = false;
}
