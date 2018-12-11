<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class model_character extends Model
{
    public $timestamps = false;

    protected $attributes = [
    	'model_id' => 0,
    	'character_id' => 0,
    	'value' => '0'
    ];
    
    protected $fillable = ['model_id', 'character_id', 'value'];
}
