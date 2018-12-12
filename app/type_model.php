<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class type_model extends Model
{
    //
    protected $fillable = array('name','icon');
    public $timestamps = false;

    public function getPathIcon()
    {
    	return Storage::url(('typemodel').'/'.$this->icon);
    }

    public function getIcon($height = 25)
    {	
    	if($this->icon)
    		return '<img title="'.$this->name.'" src="'.$this->getPathIcon().'" style="height:'.$height.'px">';
    }
}
