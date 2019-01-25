<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_client extends Model
{
   protected $fillable = [
    	'name','lastname','secondname','type_id','birthday','passport_serial',
    	'address','area_id'
    ];

    protected $guarded = [];

    protected $attributes = [
    	'name' => '0',
        'address' => '0'
    ];
    
    public $timestamps = false;

    public function traffic()
    {
        return $this->belongsTo('App\crm_traffic','id','client_id');
    }

    public function area()
    {
        return $this->hasOne('App\crm_city_list','id','area_id');
    }

    public function type()
    {
        return $this->hasOne('App\crm_client_type','id','type_id');
    }

    public function contacts()
    {
        return $this->hasMany('App\crm_client_contact','client_id','id');
    }

    public function contact()
    {
        return $this->hasOne('App\crm_client_contact','client_id','id');
    }

    public function getFullName()
    {
        if($this->lastname && $this->secondname )
            return $this->lastname.' '.mb_substr($this->name,0,1).'.'.mb_substr($this->secondname,0,1).'.';
        else if($this->lastname)
            return $this->lastname.' '.$this->name;
        else
            return $this->name;
    }
}
