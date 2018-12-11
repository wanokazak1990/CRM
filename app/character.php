<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class character extends Model
{
    protected $fillable = [
    	'name'
    ];

    protected $attributes = [
    	'name' => '0',
    ];
    
    public $timestamps = false;
}
