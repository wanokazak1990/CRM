<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_worklist_company extends Model
{
    //
    protected $fillable = ['wl_id', 'company_id', 'sum', 'rep'];
    public $timestamps = false;
}
