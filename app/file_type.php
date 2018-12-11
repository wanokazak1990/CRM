<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class file_type extends Model
{
    protected $fillable = [
    	'name',
    	'icon'
    ];

    protected $attributes = [
    	'name' => '0',
    	'icon' => '0'
    ];
    
    public $timestamps = false;

}
