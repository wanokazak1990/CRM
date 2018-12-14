<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
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

use Excel;

class CarController extends Controller
{
    //
    //
    //
    //ЭКСПОРТ АВТОМОБИЛЕЙ
    public function export(Request $request)
    {
        $query = avacar::select('avacars.*')
            ->with('brand')
            ->with('model')
            ->with('complect')
            ->with('status')
            ->with('location')
            ->with('packs')
            ->with('dops');

        if(Session::has('vin'))
            $query->where('avacars.vin','LIKE',"%Session::get('vin')%");

        if(Session::has('brand_id'))
            $query->where('avacars.brand_id',Session::get('brand_id'));

        if(Session::has('model_id'))
            $query->where('avacars.model_id',Session::get('model_id'));

        if(Session::has('complect_id'))
            $query->where('avacars.complect_id',Session::get('complect_id'));

        if(Session::has('status_id'))
            $query->where('avacars.status_id',Session::get('status_id'));

        if(Session::has('location_id'))
            $query->where('avacars.location_id',Session::get('location_id'));

        $list = $query->get();

        Excel::create('Filename', function($excel) use ($list) {
            $excel->sheet('Экспорт', function($sheet) use ($list) {
                $sheet->row(1, array(
                    '№',
                    'VIN',
                    'Бренд',
                    'Модель',
                    'Комплектация',
                    'Мотор',
                    'Пакеты',
                    'Статус',
                    'Поставка',
                    'Год выпуска',
                    'Стоимость (общая)',
                    'Дата внесения в учёт',
                    'Дата последнего изменения',
                ));
                foreach ($list as $key => $car) {
                    $sheet->row($key+2, array(
                        $key+1,
                        $car->vin,
                        $car->brand->name,
                        $car->model->name,
                        $car->complect->name,
                        $car->complect->motor->forAdmin(),
                        count($car->packs),
                        $car->status->name,
                        $car->location->name,
                        $car->year,
                        number_format($car->totalPrice(),0,'',' '),
                        $car->created_at->format('d.m.Y'),
                        $car->updated_at->format('d.m.Y')
                    ));
                }
            });
        })->export('xls');
    }

    //
    //
    //
    //СПИСОК АВТОМОБИЛЕЙ С ФИЛЬТРОМ
    public function list(Request $request, $url_get = array(),$filter = false)
    {
        if($request->has('reset'))
            return redirect()->route('carlist');

        if($request->has('export'))
            return redirect()->route('carexport')->with($request->all());

        if(!empty($request->except('page')))
            $filter = true;

    	$query = avacar::select('avacars.*')
            ->with('brand')
            ->with('model')
            ->with('complect')
            ->with('status')
            ->with('location')
            ->with('packs')
            ->with('dops');

        if($request->has('vin'))
            $query->where('avacars.vin','LIKE',"%$request->vin%");

        if($request->has('brand_id'))
            $query->where('avacars.brand_id',$request->brand_id);

        if($request->has('model_id'))
            $query->where('avacars.model_id',$request->model_id);

        if($request->has('complect_id'))
            $query->where('avacars.complect_id',$request->complect_id);

        if($request->has('status_id'))
            $query->where('avacars.status_id',$request->status_id);

        if($request->has('location_id'))
            $query->where('avacars.location_id',$request->location_id);

        $list = $query->paginate(20);
        $url_get = $request->except('page');

        $mas['brands'] = oa_brand::pluck('name','id');
        $mas['models'] = oa_model::pluck('name','id');
        $mas['complects'] = oa_complect::pluck('name','id');
        $mas['locations'] = ava_loc::pluck('name','id');
        $mas['statuses'] = ava_status::pluck('name','id');

        return view('avacars.list')
            ->with('filter',$filter)
            ->with($mas)
            ->with('url_get',$url_get)
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
        Session::put('prev_page',url()->previous());
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
        return redirect(Session::pull('prev_page','/carlist'));
    }

    public function delete($id)
    {
        Session::put('prev_page',url()->previous());
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
        return redirect(Session::pull('prev_page','/carlist'));
    }
}
