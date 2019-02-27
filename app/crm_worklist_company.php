<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_worklist_company extends Model
{
    //
    protected $fillable = ['wl_id', 'company_id', 'sum', 'rep','razdel'];
    public $timestamps = false;

    public function company()
    {
    	return $this->hasOne('App\company', 'id', 'company_id');
    }
}
