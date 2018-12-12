<?php

namespace App\Http\Controllers;
use Session;
use DB;
use Illuminate\Http\Request;
use App\oa_option;
use App\oa_brand;
use App\option_parent;
use App\option_brand;

class OptionController extends Controller
{
    //
    public function list(Request $request,$url_get=array())
    {   
        if($request->has('reset')) //если нажата кнопка очистить
            return redirect()->route('optionlist');//перенаправляем на роут без параметров

        $query = oa_option::select(DB::raw('oa_options.*,avg(oa_options.id)'))
        ->join('option_brands','option_brands.option_id','oa_options.id');//готовим запрос джоиним 

        if($request->has('brand_id'))
            $query->where('option_brands.brand_id',$request->brand_id);
        if($request->has('parent_id'))
            $query->where('oa_options.parent_id',$request->parent_id);
        
        $list = $query->groupBy('oa_options.id')->paginate(20);
        $url_get = $request->except('page');

        $brands = oa_brand::pluck('name','id');
    	$parents = option_parent::pluck('name','id');

        return view('option.list')
    		->with('title','Список оборудования')
    		->with('list',$list)
            ->with('brands',$brands)
            ->with('parents',$parents)
    		->with(['addTitle'=>'Новое оборудование','route'=>'optionadd'])
            ->with(['edit'=>'optionedit','delete'=>'optiondelete'])
            ->with('url_get',$url_get);
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

    public function edit(Request $request,$id)
    {
        Session::put('prev_page',url()->previous());
        $brandlist = oa_brand::pluck('name','id');
        $parentlist = option_parent::pluck('name','id');
        $option = new oa_option();
        $option = $option->find($id);
        return view('option.add')
            ->with('title','Редактирование оборудования')
            ->with('brands',$brandlist)
            ->with('parents',$parentlist)
            ->with('option',$option);
    }

    public function update(Request $request,$id)
    {
        $url = Session::pull('prev_page','/optionlist');
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
            return redirect($url);
        }
        return redirect($url);
    }

    public function delete($id)
    {
        Session::put('prev_page',url()->previous());
        $option = new oa_option();
        $option = $option->find($id);
        return view('option.del')//вывод вива
            ->with('title','Удаление оборудования')//заголовок
            ->with('option',$option);//модель типа
    }

    public function destroy($id)
    {
        $url = Session::pull('prev_page','/optionlist');
        if(isset($_POST['delete']))
        {
            $option = new oa_option();
            $option = $option->find($id);
            oa_option::destroy($id);
            option_brand::where('option_id',$id)->delete();
        }
        return redirect($url);
    }
}
