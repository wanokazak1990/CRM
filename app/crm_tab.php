<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_tab extends Model
{
    protected $fillable = [
    	'setting_id',
    	'field_id'
    ];
    
    public $timestamps = false;
}
