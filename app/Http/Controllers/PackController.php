<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\pack;
use App\oa_brand;
use App\pack_option;
use App\model_pack;
use App\oa_option;
use App\oa_model;
use App\complect_pack;

class PackController extends Controller
{
    //
    public function list(Request $request, $url_get = array(), $filter = false)
    {
        if($request->has('reset'))
            return redirect()->route('packlist');

        $query = pack::select(DB::raw('packs.*,avg(packs.id)'))->join('model_packs','model_packs.pack_id','=','packs.id');

        if($request->has('code'))
            $query->where('packs.code','LIKE',"%$request->code%");

        if($request->has('brand_id'))
            $query->where('packs.brand_id',$request->brand_id);
    	//$pack = pack::paginate(300);
        $pack = $query->groupBy('packs.id')->paginate(20);
        $url_get = $request->except('page');

        $mas['brands'] = oa_brand::pluck('name','id');
        $mas['models'] = oa_model::pluck('name','id');

    	return view('pack.list')
            ->with($mas)
            ->with('url_get',$url_get)
    		->with('title','Список опций')
    		->with('list',$pack)
    		->with(['route'=>'packadd','addTitle'=>'Новая опция','delete'=>'packdelete','edit'=>'packedit']);
    }

    public function add()
    {
    	$pack = new pack();
    	$brands = oa_brand::pluck('name','id');
    	return view('pack.add')
    		->with('title','Новая опция')
    		->with('brands',$brands)
    		->with('pack',$pack);
    }

    public function put(Request $request)
    {
    	if(isset($_POST['submit']))
    	{
    		$pack = new pack($request->input());
    		$pack->save();
    		if(isset($_POST['pack_model'])) :
    			foreach ($request->pack_model as $key => $model) :
    				$model_pack = new model_pack();
    				$model_pack->model_id = $model;
    				$model_pack->pack_id = $pack->id;
    				$model_pack->save();
    			endforeach;
    		endif;

    		if(isset($_POST['pack_option'])) :
    			foreach ($request->pack_option as $key => $option) :
    				$pack_option = new pack_option();
    				$pack_option->option_id = $option;
    				$pack_option->pack_id = $pack->id;
    				$pack_option->save();
    			endforeach;
    		endif;
    	}
    	return redirect()->route('packlist');
    }

    public function edit($id)
    {
        Session::put('prev_page',url()->previous());
    	$pack = pack::find($id);
    	$brands = oa_brand::pluck('name','id');
    	$model = oa_model::where('brand_id',$pack->brand_id)->get();
    	$option = oa_option::select('oa_options.id','oa_options.name','oa_options.parent_id')
    					->join('option_brands','option_brands.option_id','=','oa_options.id')
    					->where('option_brands.brand_id',$pack->brand_id)
                        ->orderBy('oa_options.parent_id', 'desc')
    					->get();
    	return view('pack.add')
    		->with('title','Изменить опцию')
    		->with('brands',$brands)
    		->with('pack',$pack)
    		->with('options',$option)
    		->with('models',$model);
    }

    public function update(Request $request,$id)
    {
    	if(isset($_POST['submit']))
    	{
    		$pack = pack::find($id);
    		if(!isset($request->type)) $pack->type = 0;
    		$pack->update($request->input());

    		model_pack::where('pack_id',$id)->delete();
    		if(isset($_POST['pack_model'])) :
    			foreach ($request->pack_model as $key => $model) :
    				$model_pack = new model_pack();
    				$model_pack->model_id = $model;
    				$model_pack->pack_id = $pack->id;
    				$model_pack->save();
    			endforeach;
    		endif;

    		pack_option::where('pack_id',$id)->delete();
    		if(isset($_POST['pack_option'])) :
    			foreach ($request->pack_option as $key => $option) :
    				$pack_option = new pack_option();
    				$pack_option->option_id = $option;
    				$pack_option->pack_id = $pack->id;
    				$pack_option->save();
    			endforeach;
    		endif;
    	}
    	return redirect(Session::pull('prev_page','/packlist'));
    }

    public function delete($id)
    {
        Session::put('prev_page',url()->previous());
    	$pack = pack::find($id);
    	return view('pack.del')
    		->with('title','Удаление опции')
    		->with('pack',$pack);
    }

    public function destroy($id)
    {
    	if(isset($_POST['delete']))
    	{
    		pack::where('id',$id)->delete();
    		model_pack::where('pack_id',$id)->delete();
    		complect_pack::where('pack_id',$id)->delete();
    		pack_option::where('pack_id',$id)->delete();
    	}
    	return redirect(Session::pull('prev_page','/packlist'));
    }
}
