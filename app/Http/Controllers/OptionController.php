<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\oa_option;
use App\oa_brand;
use App\option_parent;
use App\option_brand;

class OptionController extends Controller
{
    //
    public function list()
    {   
    	$list = oa_option::paginate(20);
    	return view('option.list')
    		->with('title','Список оборудования')
    		->with('list',$list)
    		->with(['addTitle'=>'Новое оборудование','route'=>'optionadd'])
            ->with(['edit'=>'optionedit','delete'=>'optiondelete']);
    }

    public function add()
    {
    	$brandlist = oa_brand::pluck('name','id');
        $parentlist = option_parent::pluck('name','id');

    	$option = new oa_option();
    	return view('option.add')
    		->with('title','Новое оборудование')
    		->with('brands',$brandlist)
            ->with('parents',$parentlist)
    		->with('option',$option);
    }

    public function put(Request $request)
    {   
        if(isset($_POST['submit']))
        {
            $option = new oa_option($_POST);
            $option->save(); 
            if(isset($request->brands)) :
                foreach ($request->brands as $brand) :
                    $option_brand = new option_brand();
                    $option_brand->brand_id = $brand;
                    $option_brand->option_id = $option->id;
                    $option_brand->save();
                endforeach;
            endif;
            return redirect()->route('optionlist');
        }
        return redirect()->route('optionlist');
    }

    public function edit($id)
    {
        $brandlist = oa_brand::pluck('name','id');
        $parentlist = option_parent::pluck('name','id');

        $option = new oa_option();
        $option = $option->find($id);
        return view('option.add')
            ->with('title','Новое оборудование')
            ->with('brands',$brandlist)
            ->with('parents',$parentlist)
            ->with('option',$option);
    }

    public function update(Request $request,$id)
    {
        if(isset($_POST['submit']))
        {
            $option = oa_option::find($id);
            option_brand::where('option_id',$option->id)->delete();
            if(is_array($request->brands)) :
                foreach ($request->brands as $brand) :
                    $option_brand = new option_brand();
                    $option_brand->brand_id = $brand;
                    $option_brand->option_id = $option->id;
                    $option_brand->save();
                endforeach;
            endif;
            $option->update($request->input());
            return redirect()->route('optionlist');
        }
        return redirect()->route('optionlist');
    }

    public function delete($id)
    {
        $option = new oa_option();
        $option = $option->find($id);
        return view('option.del')//вывод вива
            ->with('title','Удаление оборудования')//заголовок
            ->with('option',$option);//модель типа
    }

    public function destroy($id)
    {
        if(isset($_POST['delete']))
        {
            $option = new oa_option();
            $option = $option->find($id);
            oa_option::destroy($id);
            option_brand::where('option_id',$id)->delete();
        }
        return redirect()->route('optionlist');
    }
}
