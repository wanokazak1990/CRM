<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_all_field extends Model
{
    protected $fillable = [
    	'name',
    	'type_id'
    ];

    protected $attributes = [
    	'name' => '0',
    	'type_id' => 0
    ];
    
    public $timestamps = false;
}
