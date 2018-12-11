<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class model_pack extends Model
{
    //
    protected $fillable = ['model_id','pack_id'];
    public $timestamps = false;

    public function model()
    {
    	return $this->hasOne('App\oa_model','id','model_id');
    }
}
