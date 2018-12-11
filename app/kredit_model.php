<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class kredit_model extends Model
{
    //
    protected $fillable = ['kredit_id','model_id'];

    public function model()
    {
    	return $this->hasOne('App\oa_model','id','model_id');
    }
}
