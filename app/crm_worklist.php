<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_worklist extends Model
{
    //
    protected $fillable = array('traffic_id','client_id','manager_id');

    public function manager()
    {
    	return $this->hasOne('App\user','id','manager_id');
    }

    public function selectedModel($carModel = array())
    {
    	$selCar = crm_car_selection::with('avacar')->where('worklist_id',$this->id)->first();
		$testCar = crm_testdrive::with('model')->where('worklist_id',$this->id)->get();
		if(!empty($selCar) && $selCar->avacar->id)
			$carModel[] = $selCar->avacar->model;
		else
			foreach ($testCar as $key => $test) 
				$carModel[] = $test->model;
		return $carModel;
	}
}
