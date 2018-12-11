<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ava_status extends Model
{
    //
    protected $table = 'ava_status';
    protected $fillable = ['name'];
    public $timestamps = false;
}
