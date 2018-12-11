<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\oa_dop;
use App\oa_brand;
use App\option_parent;

/* ИСПОЛЬЗУЕТ В КАЧЕСТВЕ ВИВА, ВИВ СТАНДАРТНОГО ОБОРУДОВАНИЯ oa_option */

class DopController extends Controller
{
    public function list()
    {
    	$list = oa_dop::paginate(20);
    	return view('option.list')
    		->with('title','Список доп.оборудования')
    		->with('list',$list)
    		->with(['addTitle'=>'Новое доп.оборудование','route'=>'dopadd'])
            ->with(['edit'=>'dopedit','delete'=>'dopdelete']);
    }

    public function add()
    {
    	//$brandlist = oa_brand::pluck('name','id');
        $parentlist = option_parent::pluck('name','id');

    	$option = new oa_dop();
    	return view('option.add')
    		->with('title','Новое доп.оборудование')
    		//->with('brands',$brandlist)
            ->with('parents',$parentlist)
    		->with('option',$option);
    }

    public function put(Request $request)
    {   
        if(isset($_POST['submit']))
        {
            $option = new oa_dop($_POST);
            $option->save(); 
            return redirect()->route('doplist');
        }
        return redirect()->route('doplist');
    }

    public function edit($id)
    {
        //$brandlist = oa_brand::pluck('name','id');
        $parentlist = option_parent::pluck('name','id');

        $option = new oa_dop();
        $option = $option->find($id);
        return view('option.add')
            ->with('title','Изменение доп.оборудование')
            //->with('brands',$brandlist)
            ->with('parents',$parentlist)
            ->with('option',$option);
    }

    public function update(Request $request,$id)
    {
        if(isset($_POST['submit']))
        {
            $option = oa_dop::find($id);
            $option->update($request->input());
            return redirect()->route('doplist');
        }
        return redirect()->route('doplist');
    }

    public function delete($id)
    {
        $option = new oa_dop();
        $option = $option->find($id);
        return view('option.del')//вывод вива
            ->with('title','Удаление доп.оборудования')//заголовок
            ->with('option',$option);//модель типа
    }

    public function destroy($id)
    {
        if(isset($_POST['delete']))
        {
            oa_dop::destroy($id);
        }
        return redirect()->route('doplist');
    }
}
