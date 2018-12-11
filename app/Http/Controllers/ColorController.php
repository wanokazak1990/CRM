<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\oa_color;
use App\oa_brand;
use App\model_color;

class ColorController extends Controller
{
    //
    public function list()
    {
    	$list = oa_color::paginate(20);
        return view('color.list')
            ->with('title','Список цветов')
            ->with('list',$list)
            ->with(['addTitle'=>'Новый цвет','route'=>'coloradd'])
            ->with('edit','coloredit')
            ->with('delete','colordelete');
    }

    public function add()
    {
        $brandlist = oa_brand::pluck('name','id');
        $color = new oa_color();
        return view('color.add')
            ->with('title','Новый цвет')
            ->with('color',$color)
            ->with('brands',$brandlist);
    }

    public function put(Request $request)
    {
        if(isset($_POST['submit']))
        {
            $color = new oa_color($request->input());
            $color->save();
            return redirect()->route('colorlist');
        }
        return redirect()->route('colorlist');
    }

    public function edit($id)
    {
        $brandlist = oa_brand::pluck('name','id');
        $color = new oa_color();
        $color = $color->find($id);
        return view('color.add')
            ->with('title','Изменить цвет')
            ->with('color',$color)
            ->with('brands',$brandlist);
    }

    public function update(Request $request,$id)
    {
        if(isset($_POST['submit']))
        {
            $color = new oa_color();
            $color = $color->find($id);
            $color->update($request->input());
            return redirect()->route('colorlist');
        }
        return redirect()->route('colorlist');
    }

    public function delete($id)
    {
        $color = new oa_color();
        $color = $color->find($id);
        return view('color.del')//вывод вива
            ->with('title','Удаление цвета')//заголовок
            ->with('color',$color);//модель типа
    }

    public function destroy($id)
    {
    	if(isset($_POST['delete']))
        {
            $color = new oa_color();
            $color = $color->find($id);
            oa_color::destroy($id);
            model_color::where('color_id',$id)->delete();
        }
        return redirect()->route('colorlist');
    }
}
