<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\oa_brand;
use App\type_transmission;
use App\type_wheel;
use App\type_motor;
use App\oa_motor;

class MotorController extends Controller
{
    //
    public function list(Request $request)
    {
        if($request->has('reset'))
            return redirect()->route('motorlist');

        $mas['transmissions'] = type_transmission::pluck('name','id');
        $mas['wheels'] = type_wheel::pluck('name','id');
        $mas['types'] = type_motor::pluck('name','id');
        $mas['brands'] = oa_brand::pluck('name','id');

        $query = oa_motor::select('*');

        if($request->has('brand_id'))
            $query->where('brand_id',$request->brand_id);

        if($request->has('type_id'))
            $query->where('type_id',$request->type_id);
        
        if($request->has('transmission_id'))
            $query->where('transmission_id',$request->transmission_id);
        
        if($request->has('wheel_id'))
            $query->where('wheel_id',$request->wheel_id);
    	
        $list = $query->orderBy('brand_id')->orderBy('type_id')->orderBy('size')->orderBy('power')->get();
    	
        return view('motor.list')
            ->with($mas)
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
        Session::put('prev_page',url()->previous());
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
    	return redirect(Session::pull('prev_page','/optionlist'));
    }

    public function delete($id)
    {
        Session::put('prev_page',url()->previous());
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
    	return redirect(Session::pull('prev_page','/optionlist'));
    }
}
