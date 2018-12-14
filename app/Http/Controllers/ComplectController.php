<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\oa_complect;
use App\oa_brand;
use App\complect_color;
use App\complect_option;
use App\complect_pack;
use App\oa_model;
use App\oa_motor;
use App\oa_option;
use App\oa_color;
use App\pack;

class ComplectController extends Controller
{
    //
    public function list(Request $request, $url_get= array())
    {
        if($request->has('reset'))
            return redirect()->route('complectlist');
        $query = oa_complect::select('oa_complects.*')->with('motor')->with('brand')->with('model');

        if($request->has('brand_id'))
            $query->where('oa_complects.brand_id',$request->brand_id);
        if($request->has('model_id'))
            $query->where('oa_complects.model_id',$request->model_id);

        $url_get = $request->except('page');
    	$list = $query->paginate(20);

        $mas['brands'] = oa_brand::pluck('name','id');
        $mas['models'] = oa_model::pluck('name','id');

    	return view('complect.list')
            ->with($mas)
            ->with('url_get',$url_get)
    		->with('title','Список комплектаций')
    		->with('list',$list)
    		->with(['addTitle'=>'Новая комплектация','route'=>'complectadd'])
			->with('edit','complectedit')
			->with('delete','complectdelete');
    }

    public function add()
    {
    	$complect = new oa_complect();
    	$brands = oa_brand::pluck('name','id');
    	return view('complect.add')
    		->with('title','Новая комплектация')
    		->with('complect',$complect)
    		->with('brands',$brands);
    }

    public function put(Request $request)
    {
    	if(isset($_POST['submit']))
    	{
    		$complect = new oa_complect($request->input());
    		$complect->save();
    		if(isset($_POST['color_id'])) : 
    			foreach ($request->color_id as $key => $color) :
    				$complect_color = new complect_color(['color_id'=>$color,'complect_id'=>$complect->id]);
    				$complect_color->save();
    			endforeach;
    		endif;

    		if(isset($_POST['pack_option'])) : 
    			foreach ($request->pack_option as $key => $option) :
    				$complect_option = new complect_option(['option_id'=>$option,'complect_id'=>$complect->id]);
    				$complect_option->save();
    			endforeach;
    		endif;

    		if(isset($_POST['packs'])) : 
    			foreach ($request->packs as $key => $pack) :
    				$complect_pack = new complect_pack(['pack_id'=>$pack,'complect_id'=>$complect->id]);
    				$complect_pack->save();
    			endforeach;
    		endif;
    	}
    	return redirect()->route('complectlist');
    }

    public function edit($id)
    {
        Session::put('prev_page',url()->previous());
    	$complect = oa_complect::find($id);//беру комплектацию по ид
    	$brands = oa_brand::pluck('name','id');//масив брендов ид -> название
    	$models = oa_model::where('brand_id',$complect->brand_id)->pluck('name','id');//массив моделей в зависимости от бренда комплектации ид_модели -> название модели
    	
    	$motorCollection = oa_motor::where('brand_id',$complect->brand_id)->get();//тянем моторы в зависимости от бренда комплектации
    	$motors = array();
    	foreach ($motorCollection as $motor) //делаем масив моторов id -> название мотора (функция name())
    	{
    		$motors[$motor->id] = $motor->name(); 
    	}
    	
    	$options = oa_option::select('oa_options.*')
    				->join('option_brands','option_brands.option_id','=','oa_options.id')
    				->where('option_brands.brand_id',$complect->brand_id)
                    ->orderBy('oa_options.parent_id', 'desc')
    				->get();//тянем все опции в зависимости от того какому бренду пренадлежит комплектация
    	$colors = oa_color::select('oa_colors.*')
    					->join('model_colors','model_colors.color_id','=','oa_colors.id')
    					->where('model_colors.model_id',$complect->model_id)
    					->get();//тянем цвета в зависимости от того какая модель прицеплена к комплектации
    	$packs = pack::select('packs.*')
    					->join('model_packs','model_packs.pack_id','=','packs.id')
    					->where('model_packs.model_id',$complect->model_id)
    					->get();//тянем пакеты в зависимости от того какая модель прицеплена к комплектации
    	$installs = array(
    		'install_options'=>complect_option::where('complect_id',$complect->id)->get(),
    		'install_colors'=>complect_color::where('complect_id',$complect->id)->get(),
    		'install_packs'=>complect_pack::where('complect_id',$complect->id)->get()
    	);
    	return view('complect.add')
    		->with('title','Изменение комплектации')
    		->with('complect',$complect)
    		->with('brands',$brands)
    		->with('models',$models)
    		->with('motors',$motors)
    		->with('options',$options)
    		->with('colors',$colors)
    		->with('packs',$packs)
    		->with('installs',$installs);
    }

    public function update(Request $request,$id)
    {
    	if($request->has('submit'))
    	{
    		$complect = oa_complect::find($id);
    		$complect->update($request->input());

    		complect_color::where('complect_id',$complect->id)->delete();
    		complect_option::where('complect_id',$complect->id)->delete();
    		complect_pack::where('complect_id',$complect->id)->delete();

    		if(isset($_POST['color_id'])) : 
    			foreach ($request->color_id as $key => $color) :
    				$complect_color = new complect_color(['color_id'=>$color,'complect_id'=>$complect->id]);
    				$complect_color->save();
    			endforeach;
    		endif;

    		if(isset($_POST['pack_option'])) : 
    			foreach ($request->pack_option as $key => $option) :
    				$complect_option = new complect_option(['option_id'=>$option,'complect_id'=>$complect->id]);
    				$complect_option->save();
    			endforeach;
    		endif;

    		if(isset($_POST['packs'])) : 
    			foreach ($request->packs as $key => $pack) :
    				$complect_pack = new complect_pack(['pack_id'=>$pack,'complect_id'=>$complect->id]);
    				$complect_pack->save();
    			endforeach;
    		endif;
    	}
    	return redirect(Session::pull('prev_page','/complectlist'));
    }

    public function delete($id)
    {
        Session::put('prev_page',url()->previous());
    	$complect = oa_complect::find($id);
    	return view('complect.del')
    		->with('title','Удалить комплектацию')
    		->with('complect',$complect);
    }

    public function destroy($id)
    {
    	if(isset($_POST['delete']))
    	{
    		complect_color::where('complect_id',$id)->delete();
    		complect_option::where('complect_id',$id)->delete();
    		complect_pack::where('complect_id',$id)->delete();
    		oa_complect::destroy($id);
    	}
    	return redirect(Session::pull('prev_page','/complectlist'));
    }
}
