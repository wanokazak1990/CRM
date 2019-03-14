<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_client_contact extends Model
{
    //
    public function getMarker()
    {
    	return $this->hasone('App\crm_worklist_marker','id','marker');
    }
}
