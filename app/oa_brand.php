<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class oa_brand extends Model
{
    //
    protected $fillable = array('name');
    public $timestamps = false;

    public function getPathIcon()
    {
    	return Storage::url(('brand').'/'.$this->icon);
    }

    public function getIcon($height = 20)
    {	
    	if($this->icon)
    		return '<img title="'.$this->name.'" src="'.$this->getPathIcon().'" style="height:'.$height.'px">';
    }
}
