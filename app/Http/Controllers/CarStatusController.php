<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ava_status;

class CarStatusController extends Controller
{
    //
    public function list()
    {
        $list = ava_status::get();
        return view('status.list')
            ->with('title','Список статусов автомобилей')
            ->with('list',$list)
            ->with(['addTitle'=>'Новый пункт статусов','route'=>'carstatusadd'])
            ->with(['edit'=>'carstatusedit','delete'=>'carstatusdelete']);
    }

    public function add()
    {
        $status = new ava_status();
        return view('status.add')
            ->with('title','Новый пункт статуса')
            ->with('status',$status);
    }

    public function put(Request $request)
    {
        if($request->has('submit'))
        {
            $status = new ava_status($request->input());
            $status->save();
        }
        return redirect()->route('carstatuslist');
    }

    public function edit($id)
    {
        $status = ava_status::find($id);
        return view('status.add')
            ->with('title','Изменить пункт статуса')
            ->with('status',$status);
    }

    public function update(Request $request,$id)
    {
        if($request->has('submit'))
        {
            $status = ava_status::find($id);
            $status->update($request->input());
        }
        return redirect()->route('carstatuslist');
    }

    public function delete($id)
    {
        $status = ava_status::find($id);
        return view('status.del')
            ->with('title','Удалить пункт статуса')
            ->with('status',$status);
    }

    public function destroy(Request $request,$id)
    {
        if(isset($_POST['delete']))
        {
            ava_status::destroy($id);
        }
        return redirect()->route('carstatuslist');
    }
}
