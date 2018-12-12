<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class country_model extends Model
{
    //
    protected $fillable = array('name','flag');
    public $timestamps = false;

    public function getPathFlag()
    {
    	return Storage::url(('country').'/'.$this->flag);
    }

    public function getFlag($height = 25)
    {	
    	if($this->flag)
    		return '<img title="'.$this->name.'" src="'.$this->getPathFlag().'" style="height:'.$height.'px">';
    }
}
