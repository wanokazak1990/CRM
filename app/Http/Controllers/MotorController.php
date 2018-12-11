<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\oa_brand;
use App\type_transmission;
use App\type_wheel;
use App\type_motor;
use App\oa_motor;

class MotorController extends Controller
{
    //
    public function list()
    {
    	$list = oa_motor::get();
    	return view('motor.list')
    		->with('title','Список моторов')
    		->with('list',$list)
    		->with(['route'=>'motoradd','addTitle'=>'Новый мотор','edit'=>'motoredit','delete'=>'motordelete']);
    }

    public function add()
    {
    	$partlist['transmissions'] 	= type_transmission::pluck('name','id');
    	$partlist['wheels'] 		= type_wheel::pluck('name','id');
    	$partlist['types']			= type_motor::pluck('name','id');
    	$partlist['brands'] 		= oa_brand::pluck('name','id');
    	$motor = new oa_motor();
    	return view('motor.add')
    		->with('title','Новый мотор')
    		->with('parts',$partlist)
    		->with('motor',$motor);
    }

    public function put(Request $request)
    {
    	if(isset($_POST['submit']))
    	{
    		oa_motor::create($request->input());
    	}
    	return redirect()->route('motorlist');
    }

    public function edit($id)
    {
    	$partlist['transmissions'] 	= type_transmission::pluck('name','id');
    	$partlist['wheels'] 		= type_wheel::pluck('name','id');
    	$partlist['types']			= type_motor::pluck('name','id');
    	$partlist['brands'] 		= oa_brand::pluck('name','id');
    	$motor = oa_motor::find($id);
    	return view('motor.add')
    		->with('title','Изменить мотор')
    		->with('parts',$partlist)
    		->with('motor',$motor);
    }

    public function update(Request $request,$id)
    {
    	if(isset($_POST))
    	{
    		$motor = oa_motor::find($id);
    		$motor->update($request->input());
    	}
    	return redirect()->route('motorlist');
    }

    public function delete($id)
    {
    	$motor = oa_motor::find($id);
    	return view('motor.del')
    		->with('title','Удалить мотор')
    		->with('motor',$motor);
    }

    public function destroy($id)
    {
    	if(isset($_POST['delete']))
    	{
    		oa_motor::where('id',$id)->delete();
    	}
    	return redirect()->route('motorlist');
    }
}
