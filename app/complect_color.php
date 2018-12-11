<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class complect_color extends Model
{
    //
    protected $fillable = ['color_id','complect_id'];
    public $timestamps = false;
}
