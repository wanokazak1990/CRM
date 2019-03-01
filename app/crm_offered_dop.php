<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\oa_dop;

class crm_offered_dop extends Model
{
    public $timestamps = false;

    public function getDops($array = array())
    {
    	$ids = json_decode($this->options, true);
    	
    	foreach ($ids as $key => $value) 
    	{
    		$array[] = oa_dop::find($value);
    	}

    	return $array;
    }
}
