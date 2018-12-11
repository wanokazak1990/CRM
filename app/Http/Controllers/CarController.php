<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\avacar;
use App\oa_brand;
use App\ava_loc;
use App\ava_status;
use App\oa_dop;
use App\ava_pack;
use App\ava_dop;
use App\oa_model;
use App\pack;
use App\oa_color;
use App\oa_complect;

class CarController extends Controller
{
    //
    public function list()
    {
    	$list = avacar::paginate(20);
        return view('avacars.list')
            ->with('title','Список автомобилей')
            ->with('list',$list)
            ->with(['addTitle'=>'Новый автомобиль','route'=>'caradd'])
            ->with(['edit'=>'caredit','delete'=>'cardelete']);
    }

    public function add()
    {   
        $car = new avacar();
        $brands = oa_brand::pluck('name','id');
        $status = ava_status::pluck('name','id');
        $loc = ava_loc::pluck('name','id');
        $dops = oa_dop::get();
        return view('avacars.add')
            ->with('title','Новый автомобиль')
            ->with('car',$car)
            ->with('brands',$brands)
            ->with('status',$status)
            ->with('loc',$loc)
            ->with('dops',$dops);
    }

    public function put(Request $request)
    {
        if(isset($_POST['submit']))
        {
            $avacar = new avacar($request->input());
            $avacar->color_id = $request->color_id[0];
            if($avacar->prodaction==null)
                $avacar->prodaction = 0;
            $avacar->save();
            if($request->has('dops'))
            {
                foreach ($request->dops as $key => $item) {
                    $dop = new ava_dop(
                        [
                            'avacar_id'=>$avacar->id,
                            'dop_id'=>$item
                        ]
                    );
                    $dop->save();
                }
            }
            if($request->has('packs'))
            {
                foreach ($request->packs as $key => $item) {
                    $pack = new ava_pack(
                        [
                            'avacar_id'=>$avacar->id,
                            'pack_id'=>$item
                        ]
                    );
                    $pack->save();
                }
            }
        }
        return redirect()->route('carlist');
    }

    public function edit($id)
    {
        $car = avacar::find($id);
        $brands = oa_brand::pluck('name','id');
        $status = ava_status::pluck('name','id');
        $loc = ava_loc::pluck('name','id');
        $dops = oa_dop::get();
        $packs = pack::select('packs.*')
            ->join('complect_packs','complect_packs.pack_id','=','packs.id')
            ->where('complect_packs.complect_id',$car->complect_id)
            ->get();
        $colors = oa_color::select('oa_colors.*')
            ->join('complect_colors','complect_colors.color_id','=','oa_colors.id')
            ->where('complect_colors.complect_id',$car->complect_id)
            ->get();
        $models = oa_model::where('id',$car->model_id)->pluck('name','id');
        $complects = oa_complect::where('model_id',$car->model_id)->pluck('name','id');
        return view('avacars.add')
            ->with('title','Новый автомобиль')
            ->with('car',$car)
            ->with('brands',$brands)
            ->with('status',$status)
            ->with('loc',$loc)
            ->with('models',$models)
            ->with('dops',$dops)
            ->with('packs',$packs)
            ->with('colors',$colors)
            ->with('complects',$complects);
    }

    public function update(Request $request,$id)
    {
        if(isset($_POST['submit']))
        {
            $avacar = avacar::find($id);
            $array = $request->input(); 
            $array['color_id'] = $request->color_id[0];
            if($avacar->prodaction==null)
                $avacar->prodaction = 0;
            $avacar->update($array);

            ava_dop::where('avacar_id',$avacar->id)->delete();
            if($request->has('dops'))
            {
                foreach ($request->dops as $key => $item) {
                    $dop = new ava_dop(
                        [
                            'avacar_id'=>$avacar->id,
                            'dop_id'=>$item
                        ]
                    );
                    $dop->save();
                }
            }

            ava_pack::where('avacar_id',$avacar->id)->delete();
            if($request->has('packs'))
            {
                foreach ($request->packs as $key => $item) {
                    $pack = new ava_pack(
                        [
                            'avacar_id'=>$avacar->id,
                            'pack_id'=>$item
                        ]
                    );
                    $pack->save();
                }
            }
        }
        return redirect()->route('carlist');
    }

    public function delete($id)
    {
        $avacar = avacar::find($id);
        return view('avacars.del')
        ->with('title','Удалить автомобиль')
        ->with('car',$avacar);
    }

    public function destroy($id)
    {
        if(isset($_POST['delete']))
        {
            avacar::destroy($id);
            ava_pack::where('avacar_id',$id)->delete();
            ava_dop::where('avacar_id',$id)->delete();
        }
        return redirect()->route('carlist');
    }
}
