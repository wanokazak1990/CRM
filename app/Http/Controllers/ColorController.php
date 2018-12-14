<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\oa_color;
use App\oa_brand;
use App\model_color;

class ColorController extends Controller
{
    //
    public function list(Request $request,$url_get=array())
    {
        if($request->has('reset')) //если нажата кнопка очистить
            return redirect()->route('colorlist');//перенаправляем на роут без параметров
        $query = oa_color::select('*');

        if($request->has('brand_id'))
            $query->where('oa_colors.brand_id',$request->brand_id);

        $list = $query->paginate(20);
        $url_get = $request->except('page');
        $brands = oa_brand::pluck('name','id');

        return view('color.list')
            ->with('brands',$brands)
            ->with('title','Список цветов')
            ->with('list',$list)
            ->with(['addTitle'=>'Новый цвет','route'=>'coloradd'])
            ->with('edit','coloredit')
            ->with('url_get',$url_get)
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
        Session::put('prev_page',url()->previous());
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
        $url = Session::pull('prev_page','/optionlist');
        if(isset($_POST['submit']))
        {
            $color = new oa_color();
            $color = $color->find($id);
            $color->update($request->input());
            return redirect()->route('colorlist');
        }
        return redirect($url);
    }

    public function delete($id)
    {
        Session::put('prev_page',url()->previous());
        $color = new oa_color();
        $color = $color->find($id);
        return view('color.del')//вывод вива
            ->with('title','Удаление цвета')//заголовок
            ->with('color',$color);//модель типа
    }

    public function destroy($id)
    {
        $url = Session::pull('prev_page','/optionlist');
    	if(isset($_POST['delete']))
        {
            $color = new oa_color();
            $color = $color->find($id);
            oa_color::destroy($id);
            model_color::where('color_id',$id)->delete();
        }
        return redirect($url);
    }
}
