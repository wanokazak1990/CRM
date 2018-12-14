<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\kredit;
use App\kredit_model;
use App\oa_model;
use App\oa_brand;

class KreditController extends Controller
{
    //
    public function list()
    {
    	$list = kredit::with('model')->get();
    	foreach ($list as $key => $kredit) 
    	{
    		$kredit->model->load('model');
    	}
    	return view('kredit.list')
    		->with('title','Список кредитов')
    		->with('list',$list)
    		->with(['addTitle'=>'Новый кредит','route'=>'kreditadd'])
            ->with(['edit'=>'kreditedit','delete'=>'kreditdelete']);
    }

    public function add()
    {
    	$kredit = new kredit();
    	$brands = oa_brand::pluck('name','id');
    	return view('kredit.add')
    		->with('title','Новый кредит')
    		->with('kredit',$kredit)
    		->with('brands',$brands);
    }

    public function put(Request $request)
    {
    	if($request->has('submit'))
    	{
    		$kredit = new kredit($request->input());
    		$kredit->day_in = strtotime($kredit->day_in);
    		$kredit->day_out = strtotime($kredit->day_out);
    		
    		$kredit->save();

    		foreach ($request->file() as $key=>$file) {
     			$name = $key.$kredit->id.'.'.pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
     			$kredit->banner = $name;
     			$file->move(storage_path('app/public/kredit/'), $name);
            }
            $kredit->update();
    		if($request->has('pack_model')) :
    			foreach ($request->pack_model as $key => $model) :
    				$kredit_model = new kredit_model(['kredit_id'=>$kredit->id,'model_id'=>$model]);
    				$kredit_model->save();
    			endforeach;
    		endif;
    	}
    	return redirect()->route('kreditlist');
    }

    public function edit($id)
    {
    	$kredit = kredit::find($id);
    	$brands = oa_brand::pluck('name','id');
    	$models = oa_model::where('brand_id',$kredit->brand_id)->get();
    	return view('kredit.add')
    		->with('title','Изменить кредит')
    		->with('kredit',$kredit)
    		->with('brands',$brands)
    		->with('models',$models);
    }

    public function update(Request $request,$id)
    {
    	if($request->has('submit'))
    	{	
    		$array = $request->input();
    		$kredit = kredit::find($id);
    		$array['day_in'] = strtotime($array['day_in']);
    		$array['day_out'] = strtotime($array['day_out']);

    		foreach ($request->file() as $key=>$file) {
     			$name = $key.$kredit->id.'.'.pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
     			$kredit->banner = $name;
     			$file->move(storage_path('app/public/kredit/'), $name);
            }
    		
    		$kredit->update($array);

    		kredit_model::where('kredit_id',$kredit->id)->delete();
    		if($request->has('pack_model')) :
    			foreach ($request->pack_model as $key => $model) :
    				$kredit_model = new kredit_model(['kredit_id'=>$kredit->id,'model_id'=>$model]);
    				$kredit_model->save();
    			endforeach;
    		endif;
    	}
    	return redirect()->route('kreditlist');
    }

    public function delete($id)
    {
    	$kredit = kredit::find($id);
    	return view('kredit.del')
    		->with('title','Удаить кредит')
    		->with('kredit',$kredit);
    }

    public function destroy($id)
    {
    	if(isset($_POST['delete']))
    	{
    		$kredit = kredit::find($id);
    		@unlink(storage_path('app/public/kredit/'.$kredit->banner));
    		kredit::destroy($id);
    		kredit_model::where('kredit_id',$id)->delete();
    	}
    	return redirect()->route('kreditlist');
    }
}
