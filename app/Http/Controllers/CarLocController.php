<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ava_loc;

class CarLocController extends Controller
{
    //
    public function list()
    {
    	$list = ava_loc::get();
        return view('location.list')
            ->with('title','Список поставок автомобилей')
            ->with('list',$list)
            ->with(['addTitle'=>'Новый пункт поставки','route'=>'carlocadd'])
            ->with(['edit'=>'carlocedit','delete'=>'carlocdelete']);
    }

    public function add()
    {
        $loc = new ava_loc();
        return view('location.add')
            ->with('title','Новый пункт поставки')
            ->with('loc',$loc);
    }

    public function put(Request $requset)
    {
        if($requset->has('submit'))
        {
            $loc = new ava_loc($requset->input());
            $loc->save();
        }
        return redirect()->route('carloclist');
    }

    public function edit($id)
    {
        $loc = ava_loc::find($id);
        return view('location.add')
            ->with('title','Изменить пункт поставки')
            ->with('loc',$loc);
    }

    public function update(Request $requset, $id)
    {
        if($requset->has('submit'))
        {
            $loc = ava_loc::find($id);
            $loc->update($requset->input());
        }
        return redirect()->route('carloclist');
    }

    public function delete($id)
    {
        $loc = ava_loc::find($id);
        return view('location.del')
            ->with('title','Удалить пункт поставки')
            ->with('loc',$loc);
    }

    public function destroy($id)
    {
        if(isset($_POST['delete']))
        {
            ava_loc::destroy($id);
        }
        return redirect()->route('carloclist');
    }
}
