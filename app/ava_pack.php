<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ava_pack extends Model
{
    //
    public $timestamps = false;
    protected $fillable = ['pack_id','avacar_id'];

    public function pack()
    {
    	return $this->hasOne('App\pack','id','pack_id');
    }
}
