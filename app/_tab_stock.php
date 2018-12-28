<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class _tab_stock extends Model
{

    public function prepare()
    {
   		
    	$car = avacar::find($this->id);

   		if ($car != null)
    	{
			$this->prodaction 	= $car->prodaction;
			$this->location 	= @$car->location->name;
			$this->year 		= $car->year;
			$this->brand 	 	= @$car->brand->name;
			$this->model 		= @$car->model->name;
			$this->complect  	= @$car->complect->name;
			$this->color_code 	= @$car->color->web_code;
			$this->color_name 	= @$car->color->name;
			$this->vin 			= $car->vin;
			$this->dopprice 	= $car->dopprice;

    		return $this;
    	}
    	else
    		return null;
    }
}
