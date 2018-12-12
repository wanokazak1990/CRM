<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_client extends Model
{
   protected $fillable = [
    	'name',
        'birthday',
    	'phone',
    	'email',
    	'address'
    ];

    protected $attributes = [
    	'name' => '0',
        'birthday' => 0,
    	'phone' => '0',
    	'email' => '0',
        'address' => '0'
    ];
    
    public $timestamps = false;
}
