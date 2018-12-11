<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class model_color extends Model
{
    //
    protected $attributes = [
        'model_id' => '0',
        'color_id' => '0'
    ];
    protected $fillable = ['model_id','color_id'];
    public $timestamps = false;
}
