<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\oa_color;
use App\oa_option;
use App\oa_model;
use App\oa_motor;
use App\type_motor;
use App\type_transmission;
use App\type_wheel;
use App\pack;
use App\oa_complect;

class AjaxController extends Controller
{
    //
    public function getcolor(Request $request)
    {
    	$colorlist = array();
    	$color = new oa_color();
    	if(isset($_POST['brand_id']))
    	{
    		$colorlist = $color->where('brand_id',$request->brand_id)->get();
    	}
    	if(isset($_POST['model_id']))
    	{
    		$colorlist = $color->select('oa_colors.*')
    						->join('model_colors','model_colors.color_id','=','oa_colors.id')
    						->where('model_colors.model_id',$request->model_id)
    						->get();
    	}
    	if(isset($_POST['complect_id']))
    	{
    		$colorlist = $color->select('oa_colors.*')
    			->join('complect_colors','complect_colors.color_id','=','oa_colors.id')
    			->where('complect_colors.complect_id',$request->complect_id)
    			->get();
    	}
    	echo json_encode($colorlist);
    }

    public function getOption(Request $request)
    {
    	$option = new oa_option();
    	$optionlist = $option
			    		->select('oa_options.id','oa_options.name','oa_options.parent_id')
			    		->join('option_brands','option_brands.option_id','=','oa_options.id')
			    		->where('brand_id',$request->brand_id)
			    		->orderBy('oa_options.parent_id', 'desc')
			    		->get();
    	echo json_encode($optionlist);
    }

    public function getmodels(Request $request)
    {
    	$model = new oa_model();
    	$modellist = $model->where('brand_id',$request->brand_id)->get();
    	echo json_encode($modellist);
    }

    public function getmotors(Request $request)
    {
    	$result = array();
    	$motor = new oa_motor();
    	$motorlist = $motor->where('brand_id',$request->brand_id)->get();
    	foreach ($motorlist as $key => $item) {
    		$result[] = $item->nameForSelect();
    	}
    	echo json_encode($result);
    }

    public function getpacks(Request $request)
    {
    	$packlist = array();
    	if(isset($_POST['model_id']))
    	{
	    	$packlist = pack::select('packs.*')
	    				->join('model_packs','model_packs.pack_id','=','packs.id')
	    				->where('model_packs.model_id',$request->model_id)
	    				->get();
    	}
    	if(isset($_POST['complect_id']))
    	{
	    	$packlist = pack::select('packs.*')
	    				->join('complect_packs','complect_packs.pack_id','=','packs.id')
	    				->where('complect_packs.complect_id',$request->complect_id)
	    				->get();
    	}
    	foreach ($packlist as $key => $pack) 
    	{
    		$options = $pack->option;
    		$pack->optionlist = '';
    		foreach ($options as $key => $option) 
    		{
    			$pack->optionlist .= $option->option->name;
    		}
    	}
    	echo json_encode($packlist);
    }

    public function getcomplects(Request $request)
    {	
    	$complects = oa_complect::where('model_id',$request->input('model_id'))->get();
    	foreach ($complects as $key => $complect) {
    		if($complect->motor)
    			$complects[$key]->fullname = $complect->name.' '.$complect->motor->nameForSelect()['name'] ;
    	}
    	echo json_encode($complects);
    }
}
