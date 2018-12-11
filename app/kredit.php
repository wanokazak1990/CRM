<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class kredit extends Model
{
    //
    protected $fillable = [
    	'name','day_in','day_out','rate','banner','pay','period','contibution','disklamer','dopoption','brand_id'
    ];

    public function model()
    {
    	return $this->hasMany('App\kredit_model','kredit_id','id');
    }
}
