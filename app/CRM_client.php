<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CRM_client extends Model
{
   protected $fillable = [
    	'name',
    	'phone',
    	'email',
    	'address'
    ];

    protected $attributes = [
    	'name' => '0',
    	'phone' => '0',
    	'email' => '0',
        'address' => '0'
    ];
    
    public $timestamps = false;
}
