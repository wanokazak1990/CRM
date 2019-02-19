<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class complect_pack extends Model
{
    //
    protected $fillable = ['pack_id','complect_id'];
    public $timestamps = false;

    public function pack()
    {
    	return $this->hasOne('App\pack','id','pack_id')->with('option');
    }
}
