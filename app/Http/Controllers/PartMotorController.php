<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\type_transmission;
use App\type_wheel;
use App\type_motor;
class PartMotorController extends Controller
{
    //
    public function list()
    {
    	$transmission_list = type_transmission::get();
    	$motortype_list = type_motor::get();
    	$wheel_list = type_wheel::get();
    	return view('partmotor.list')
    		->with('transmissions',$transmission_list)
    		->with('motortypes',$motortype_list)
    		->with('wheels',$wheel_list)
    		->with('title','Составляющие мотора');
    }

    public function put(Request $request)
    {
    	if(isset($request->hidden)) :
    		switch ($request->hidden) :
    			case '1':
    				type_transmission::create($request->input());
    				break;

    			case '2':
    				type_wheel::create($request->input());
    				break;

    			case '3':
    				type_motor::create($request->input());
    				break;
    			
    			default:
    				# code...
    				break;
    		endswitch;
    	endif;

    	return redirect()->route('partmotorlist');
    }

    public function destroy(Request $request)
    {	
    	if(isset($request->hidden)) :
    		switch ($request->hidden) :
    			case '1':
    				type_transmission::where('id',$request->id)->delete();
    				break;

    			case '2':
    				type_wheel::where('id',$request->id)->delete();
    				break;

    			case '3':
    				type_motor::where('id',$request->id)->delete();
    				break;
    			
    			default:
    				# code...
    				break;
    		endswitch;
    	endif;

    	return redirect()->route('partmotorlist');
    }

}
