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
use App\oa_option;
use App\complect_pack;
use DB;
use Excel;

use App\ava_date_build;
use App\ava_date_planned;
use App\ava_date_notification;
use App\ava_date_order;
use App\ava_date_ready;
use App\ava_date_ship;
use App\ava_discount;
use App\ava_shipterm;
use App\crm_car_selection;
use App\ava_data_provision;
/*
$all = avacar::get();
foreach ($all as $key => $car) 
{
    if(strlen($car->prodaction)==4)
    {
        $day = mb_substr($car->prodaction, 0,2);
        $month = mb_substr($car->prodaction, 2,4);
        $year = $car->year;
        $date = $day.'.'.$month.'.'.$year;
        $date = strtotime($date);
        $car->prodaction = $date;
    } 
    if(strlen($car->prodaction)==3)
    {
        $day = mb_substr($car->prodaction, 0,1); 
        $month = mb_substr($car->prodaction, 1,3);
        $year = $car->year;
        $date = $day.'.'.$month.'.'.$year;
        $date = strtotime($date);
        $car->prodaction = $date;
    }
    $car->update();
}
*/

class CarController extends Controller
{
    //
    //
    //
    //ЭКСПОРТ АВТОМОБИЛЕЙ
    public function export(Request $request)
    {
        

        if(Session::has('option'))
        {
            $query = avacar::select(DB::raw('avacars.*,avg(_view_caroption.id)'));
            $query->join('_view_caroption','_view_caroption.id','=','avacars.id');
        }
        else{
            $query = avacar::select('avacars.*');
        }
            
        $query  ->with('brand')
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
        
        if(Session::has('option'))
            $query->whereIn('_view_caroption.filter_order', Session::get('option'));
        
        $query->groupBy('avacars.id');

        if(Session::has('option'))
            $query->having(DB::raw('COUNT(_view_caroption.id)'),'=',count(Session::get('option')));

        $list = $query->where('status_id','<>',4)->get();

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
                    $pack_str = '';
                    if(isset($car->packs)):
                        foreach($car->packs as $pack) :
                            $pack_str .= $pack->pack->code.' ';
                        endforeach;
                    endif;
                    $sheet->row($key+2, array(
                        $key+1,
                        $car->vin,
                        $car->brand->name,
                        $car->model->name,
                        $car->complect->name,
                        $car->complect->motor->forAdmin(),
                        $pack_str,
                        $car->status->name,
                        $car->location->name,
                        $car->year,
                        number_format($car->totalPrice(),0,'',' '),
                        ($car->created_at)?$car->created_at->format('d.m.Y'):"n/a",
                        ($car->updated_at)?$car->updated_at->format('d.m.Y'):"n/a"
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
        if($request->has('reset'))//Если ресет 
            return redirect()->route('carlist');//то перенаправляем на эту же страницу но без параметров фильтрации

        if($request->has('export'))//если экспорт
            return redirect()->route('carexport')->with($request->all());//то перенаправляем на страницу экспорта, сохраняем в сессию гет-параметры

        if(!empty($request->except('page')))//если в гет есть какой либо параметр кроме пэйдж
            $filter = true;//тозначит фильтр используется

        if($request->has('option'))//если в фильтре выбраны оборудование
        {
            $query = avacar::select(DB::raw('avacars.*,avg(_view_caroption.id)'));//готовим жирный запрос
            $query->join('_view_caroption','_view_caroption.id','=','avacars.id');//присоеденяем скуль_вив с оборудованием машины
        }
        else{//иначе
            $query = avacar::select('avacars.*');//просто тащим всё из таблицы машин
        }
        //жадные запросы
        $query  ->with('brand')//получить модель бренд
                ->with('model')//модель модель
                ->with('complect')//модель комплектации
                ->with('status')//модель статуса
                ->with('location')//модель поставки
                ->with('packs')//набор моделей установленных пакетов
                ->with('dops');//набор моделей допов

        if($request->has('vin'))//если фильтр вин не пуст
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
        
        if($request->has('option'))
            $query->whereIn('_view_caroption.filter_order', $request->option);
        
        $query->groupBy('avacars.id');

        if($request->has('option'))
            $query->having(DB::raw('COUNT(_view_caroption.id)'),'=',count($request->option));

        $list = $query->where('status_id','<',4)->paginate(20);

        $url_get = $request->except('page');

        $mas['brands'] = oa_brand::pluck('name','id');
        $mas['models'] = oa_model::pluck('name','id');
        $mas['complects'] = oa_complect::pluck('name','id');
        $mas['locations'] = ava_loc::pluck('name','id');
        $mas['statuses'] = ava_status::take(3)->pluck('name','id');

        $options_list = oa_option::orderBy('filter_order')->pluck('filtered','filter_order');
        
        return view('avacars.list')
            ->with('filter',$filter)
            ->with($mas)
            ->with('url_get',$url_get)
            ->with('title','Список автомобилей')
            ->with('list',$list)
            ->with(['addTitle'=>'Новый автомобиль','route'=>'caradd'])
            ->with('options_list',$options_list)
            ->with(['edit'=>'caredit','delete'=>'cardelete']);
    }





    //СПИСОК АРХИВНЫХ АВТОМОБИЛЕЙ С ФИЛЬТРОМ
    public function archive(Request $request, $url_get = array(),$filter = false)
    {        
        if($request->has('reset'))//Если ресет 
            return redirect()->route('carlist');//то перенаправляем на эту же страницу но без параметров фильтрации

        if($request->has('export'))//если экспорт
            return redirect()->route('carexport')->with($request->all());//то перенаправляем на страницу экспорта, сохраняем в сессию гет-параметры

        if(!empty($request->except('page')))//если в гет есть какой либо параметр кроме пэйдж
            $filter = true;//тозначит фильтр используется

        if($request->has('option'))//если в фильтре выбраны оборудование
        {
            $query = avacar::select(DB::raw('avacars.*,avg(_view_caroption.id)'));//готовим жирный запрос
            $query->join('_view_caroption','_view_caroption.id','=','avacars.id');//присоеденяем скуль_вив с оборудованием машины
        }
        else{//иначе
            $query = avacar::select('avacars.*');//просто тащим всё из таблицы машин
        }
        //жадные запросы
        $query  ->with('brand')//получить модель бренд
                ->with('model')//модель модель
                ->with('complect')//модель комплектации
                ->with('status')//модель статуса
                ->with('location')//модель поставки
                ->with('packs')//набор моделей установленных пакетов
                ->with('dops');//набор моделей допов

        if($request->has('vin'))//если фильтр вин не пуст
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
        
        if($request->has('option'))
            $query->whereIn('_view_caroption.filter_order', $request->option);
        
        $query->groupBy('avacars.id');

        if($request->has('option'))
            $query->having(DB::raw('COUNT(_view_caroption.id)'),'=',count($request->option));

        $list = $query->where('status_id','=',4)->paginate(20);

        $url_get = $request->except('page');

        $mas['brands'] = oa_brand::pluck('name','id');
        $mas['models'] = oa_model::pluck('name','id');
        $mas['complects'] = oa_complect::pluck('name','id');
        $mas['locations'] = ava_loc::pluck('name','id');
        $mas['statuses'] = ava_status::pluck('name','id');

        $options_list = oa_option::orderBy('filter_order')->pluck('filtered','filter_order');
        
        return view('avacars.list')
            ->with('filter',$filter)
            ->with($mas)
            ->with('url_get',$url_get)
            ->with('title','Список архивных автомобилей')
            ->with('list',$list)
            ->with(['addTitle'=>'Новый автомобиль','route'=>'caradd'])
            ->with('options_list',$options_list)
            ->with(['edit'=>'caredit','delete'=>'cardelete']);
    }


    //СПИСОК ПРОДАННЫХ АВТОМОБИЛЕЙ С ФИЛЬТРОМ
    public function sold(Request $request, $url_get = array(),$filter = false)
    {        
        if($request->has('reset'))//Если ресет 
            return redirect()->route('carlist');//то перенаправляем на эту же страницу но без параметров фильтрации

        if($request->has('export'))//если экспорт
            return redirect()->route('carexport')->with($request->all());//то перенаправляем на страницу экспорта, сохраняем в сессию гет-параметры

        if(!empty($request->except('page')))//если в гет есть какой либо параметр кроме пэйдж
            $filter = true;//тозначит фильтр используется

        if($request->has('option'))//если в фильтре выбраны оборудование
        {
            $query = avacar::select(DB::raw('avacars.*,avg(_view_caroption.id)'));//готовим жирный запрос
            $query->join('_view_caroption','_view_caroption.id','=','avacars.id');//присоеденяем скуль_вив с оборудованием машины
        }
        else{//иначе
            $query = avacar::select('avacars.*');//просто тащим всё из таблицы машин
        }
        //жадные запросы
        $query  ->with('brand')//получить модель бренд
                ->with('model')//модель модель
                ->with('complect')//модель комплектации
                ->with('status')//модель статуса
                ->with('location')//модель поставки
                ->with('packs')//набор моделей установленных пакетов
                ->with('dops');//набор моделей допов

        if($request->has('vin'))//если фильтр вин не пуст
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
        
        if($request->has('option'))
            $query->whereIn('_view_caroption.filter_order', $request->option);
        
        $query->groupBy('avacars.id');

        if($request->has('option'))
            $query->having(DB::raw('COUNT(_view_caroption.id)'),'=',count($request->option));

        $list = $query->where('status_id','=',5)->paginate(20);

        $url_get = $request->except('page');

        $mas['brands'] = oa_brand::pluck('name','id');
        $mas['models'] = oa_model::pluck('name','id');
        $mas['complects'] = oa_complect::pluck('name','id');
        $mas['locations'] = ava_loc::pluck('name','id');
        $mas['statuses'] = ava_status::pluck('name','id');

        $options_list = oa_option::orderBy('filter_order')->pluck('filtered','filter_order');
        
        return view('avacars.list')
            ->with('filter',$filter)
            ->with($mas)
            ->with('url_get',$url_get)
            ->with('title','Список проданных автомобилей')
            ->with('list',$list)
            ->with(['addTitle'=>'Новый автомобиль','route'=>'caradd'])
            ->with('options_list',$options_list)
            ->with(['edit'=>'caredit','delete'=>'cardelete']);
    }




    public function add()
    {   
        $car = new avacar();
        $brands = oa_brand::pluck('name','id');
        $status = ava_status::pluck('name','id');
        $loc = ava_loc::pluck('name','id');
        $dops = oa_dop::orderBy('parent_id')->orderBy('name')->get();
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
        echo $request->ajax();
        //return redirect()->route('carlist');
    }

    public function edit($id)
    {
        Session::put('prev_page',url()->previous());
        $car = avacar::find($id);
        $brands = oa_brand::pluck('name','id');
        $status = ava_status::pluck('name','id');
        $loc = ava_loc::pluck('name','id');
        $dops = oa_dop::orderBy('parent_id')->orderBy('name')->get();
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
            /*avacar::destroy($id);            
            ava_pack::where('avacar_id',$id)->delete();
            ava_dop::where('avacar_id',$id)->delete();*/
            $avacar = avacar::find($id);
            $avacar->status_id = 4;
            $avacar->update();
        }
        return redirect(Session::pull('prev_page','/carlist'));
    }

    public function ajaxput(Request $request)
    {   print_r($request->all());
        if(!$request->id)
        {   
            $avacar = new avacar($request->input());

            if($avacar->date_storage!='')
                $avacar->date_storage = strtotime($request->date_storage);
            if($avacar->date_preparation!='')
                $avacar->date_preparation = strtotime($request->date_preparation);
            if($avacar->receipt_date!='')
                $avacar->receipt_date = strtotime($request->receipt_date);
            if($avacar->pts_datepay!='')
                $avacar->pts_datepay = strtotime($request->pts_datepay);
            if($avacar->pts_datereception!='')
                $avacar->pts_datereception = strtotime($request->pts_datereception);
            if($avacar->debited_date!='')
                $avacar->debited_date = strtotime($request->debited_date);
            if($avacar->logist_date!='')
                $avacar->logist_date = strtotime($request->logist_date);

            $avacar->brand_id = oa_model::find($request->model_id)->brand_id;
            $avacar->provision = $request->st_provision;

            $avacar->estimated_purchase    = str_replace(' ', '', $request->estimated_purchase);
            $avacar->actual_purchase       = str_replace(' ', '', $request->actual_purchase);
            $avacar->shipping_discount     = str_replace(' ', '', $request->shipping_discount);
            
            $avacar->save();

            if($request->has('packs'))//записываю пакеты выбранные
                foreach ($request->packs as $key => $item)
                    $pack = ava_pack::create(['avacar_id'=>$avacar->id,'pack_id'=>$item]);

            if($request->has('dops'))//записываю допы выбранные
                foreach ($request->dops as $key => $item) 
                    $dop = ava_dop::create(['avacar_id'=>$avacar->id,'dop_id'=>$item]);

            if($request->has('date_order'))//дата заявки
            {
                $date = strtotime($request->date_order);
                $order = ava_date_order::create(['car_id'=>$avacar->id,'date'=>$date]);
            }

            //ПЕРЕДЕЛАТЬ ПОД МАССИВ (ТАК КАК ДАТ ПЛАНИРУЕМОЙ СБОРКИ МОЖЕТ БЫТЬ МНОГО)
            if($request->has('date_planned'))//планируемая дата сборки
            {
                foreach ($request->date_planned as $key => $value) {
                    $date = strtotime($value);
                    $order = ava_date_planned::create(['car_id'=>$avacar->id,'date'=>$date]);
                }
            }

            if($request->has('date_notification'))//уведомление о сборке
            {
                $date = strtotime($request->date_notification);
                $order = ava_date_notification::create(['car_id'=>$avacar->id,'date'=>$date]);
            }
            if($request->has('date_build'))//дата сборки фактическая
            {
                $date = strtotime($request->date_build);
                $order = ava_date_build::create(['car_id'=>$avacar->id,'date'=>$date]);
            }
            if($request->has('date_ready'))//готовность к отгрузке
            {
                $date = strtotime($request->date_ready);
                $order = ava_date_ready::create(['car_id'=>$avacar->id,'date'=>$date]);
            }
            //ПЕРЕДЕЛАТЬ ПОД МАССИВ (ТАК КАК ДАТ ОТГРУЗКИ МОЖЕТ БЫТЬ МНОГО)
            if($request->has('date_ship'))//дата отгрузки
            {
                foreach ($request->date_ship as $key => $value) {
                    $date = strtotime($value);
                    $order = ava_date_ship::create(['car_id'=>$avacar->id,'date'=>$date]);
                }
            }

            if($request->has('st_provision'))
            {
                foreach ($request->st_delay as $key => $value) {
                    if($request->st_date[$key])
                        $res = ava_data_provision::create([
                            'car_id'=>$avacar->id,
                            'count_days'=>$request->st_delay[$key],
                            'date_provision'=>strtotime($request->st_date[$key])
                        ]);
                }
            }

            if($request->has('dc_type'))
            {   
                if(count($request->dc_type) == count($request->dc_sale))
                {
                    foreach ($request->dc_type as $key => $value) {
                        $res = ava_discount::create([
                            'car_id'=>$avacar->id,
                            'type'=>$request->dc_type[$key],
                            'sale'=>str_replace(' ', '', $request->dc_sale[$key]),
                            'pack_percent'=>str_replace(',', '.', $request->dc_pack_percent[$key])
                        ]);
                    }
                }
            }
        }

        else{
            $avacar = avacar::find($request->id);
            $avacar->update($request->input());
            if($avacar->date_storage!='')
                $avacar->date_storage = strtotime($request->date_storage);
            if($avacar->date_preparation!='')
                $avacar->date_preparation = strtotime($request->date_preparation);
            if($avacar->receipt_date!='')
                $avacar->receipt_date = strtotime($request->receipt_date);
            if($avacar->pts_datepay!='')
                $avacar->pts_datepay = strtotime($request->pts_datepay);
            if($avacar->pts_datereception!='')
                $avacar->pts_datereception = strtotime($request->pts_datereception);
            if($avacar->debited_date!='')
                $avacar->debited_date = strtotime($request->debited_date);
            if($avacar->logist_date!='')
                $avacar->logist_date = strtotime($request->logist_date);
            if(!isset($request->logist_marker))
                $avacar->logist_marker = '';

            $avacar->brand_id = oa_model::find($request->model_id)->brand_id;
            $avacar->provision = $request->st_provision;

            $avacar->estimated_purchase    = str_replace(' ', '', $request->estimated_purchase);
            $avacar->actual_purchase       = str_replace(' ', '', $request->actual_purchase);
            $avacar->shipping_discount     = str_replace(' ', '', $request->shipping_discount);
            
            $avacar->save();

            ava_pack::where('avacar_id',$avacar->id)->delete();
            if($request->has('packs'))//записываю пакеты выбранные
                foreach ($request->packs as $key => $item)
                    $pack = ava_pack::create(['avacar_id'=>$avacar->id,'pack_id'=>$item]);

            ava_dop::where('avacar_id',$avacar->id)->delete();
            if($request->has('dops'))//записываю допы выбранные
                foreach ($request->dops as $key => $item) 
                    $dop = ava_dop::create(['avacar_id'=>$avacar->id,'dop_id'=>$item]);

            ava_date_order::where('car_id',$avacar->id)->delete();
            if($request->has('date_order'))//дата заявки
            {
                $date = strtotime($request->date_order);
                $order = ava_date_order::create(['car_id'=>$avacar->id,'date'=>$date]);
            }

            //ПЕРЕДЕЛАТЬ ПОД МАССИВ (ТАК КАК ДАТ ПЛАНИРУЕМОЙ СБОРКИ МОЖЕТ БЫТЬ МНОГО)
            ava_date_planned::where('car_id',$avacar->id)->delete();
            if($request->has('date_planned'))//планируемая дата сборки
            {
                foreach ($request->date_planned as $key => $value) {
                    $date = strtotime($value);
                    $order = ava_date_planned::create(['car_id'=>$avacar->id,'date'=>$date]);
                }
            }

            ava_date_notification::where('car_id',$avacar->id)->delete();
            if($request->has('date_notification'))//уведомление о сборке
            {
                $date = strtotime($request->date_notification);
                $order = ava_date_notification::create(['car_id'=>$avacar->id,'date'=>$date]);
            }
            
            ava_date_build::where('car_id',$avacar->id)->delete();
            if($request->has('date_build'))//дата сборки фактическая
            {
                $date = strtotime($request->date_build);
                $order = ava_date_build::create(['car_id'=>$avacar->id,'date'=>$date]);
            }

            ava_date_ready::where('car_id',$avacar->id)->delete();
            if($request->has('date_ready'))//готовность к отгрузке
            {
                $date = strtotime($request->date_ready);
                $order = ava_date_ready::create(['car_id'=>$avacar->id,'date'=>$date]);
            }

            //ПЕРЕДЕЛАТЬ ПОД МАССИВ (ТАК КАК ДАТ ОТГРУЗКИ МОЖЕТ БЫТЬ МНОГО)
            ava_date_ship::where('car_id',$avacar->id)->delete();
            if($request->has('date_ship'))//дата отгрузки
            {   
                foreach ($request->date_ship as $key => $value) {
                    $date = strtotime($value);
                    $order = ava_date_ship::create(['car_id'=>$avacar->id,'date'=>$date]);
                }
            }

            ava_data_provision::where('car_id',$avacar->id)->delete();
            if($request->has('st_provision'))
            {   //echo ' 000 '; print_r($request->st_date);
                foreach ($request->st_delay as $key => $value) {
                    if($request->st_date[$key])
                        $res = ava_data_provision::create([
                            'car_id'=>$avacar->id,
                            'count_days'=>$request->st_delay[$key],
                            'date_provision'=>strtotime($request->st_date[$key])
                        ]);
                }
            }

            ava_discount::where('car_id',$avacar->id)->delete();
            if($request->has('dc_type'))
            {   
                if(count($request->dc_type) == count($request->dc_sale))
                {
                    foreach ($request->dc_type as $key => $value) {
                        $res = ava_discount::create([
                            'car_id'=>$avacar->id,
                            'type'=>$request->dc_type[$key],
                            'sale'=>str_replace(' ', '', $request->dc_sale[$key]),
                            'pack_percent'=>str_replace(',', '.', $request->dc_pack_percent[$key])
                        ]);
                    }
                }
            }
        }
    }

    public function open(Request $request )
    {
        $car = avacar::with('getAuthor')
            ->with('getDateOrder')
            ->with('getDatePlannedAll')
            ->with('getDateBuild')
            ->with('getDateReady')
            ->with('getDateShipAll')
            ->with('getDateNotification')
            ->with('brand')
            ->with('model')
            ->with('complect')
            ->with('color')
            ->with('dops')
            ->with('getDateProvision')
            ->with('getDiscount')
            ->find($request->id);
        $car->model->country;

        $car->packprice = $car->packPrice();

        $packs = pack::select('packs.*')
            ->with('option')
            ->join('complect_packs','complect_packs.pack_id','=','packs.id')
            ->join('pack_options','pack_options.pack_id','=','packs.id')
            ->join('oa_options','oa_options.id','=','pack_options.option_id')
            ->where('complect_packs.complect_id',$car->complect_id)
            ->groupBy('packs.id')
            ->get();

        $colors = oa_color::select('oa_colors.*')
            ->join('complect_colors','complect_colors.color_id','=','oa_colors.id')
            ->where('complect_colors.complect_id',$car->complect_id)
            ->get();
        $complects = oa_complect::where('model_id',$car->model_id)->get();
        foreach ($complects as $key => $complect) {
            if($complect->motor)
                $complects[$key]->fullname = $complect->name.' '.$complect->motor->getEasyName();
        }

        echo json_encode([
                'car'=>$car,
                'complects'=>$complects,
                'colors'=>$colors,
                'packs'=>$packs
        ]);
    }
    
    public function getPriceCarByWLId(Request $request, $array = array())
    {
        if(!$request->has('wl_id')) return false;
        $selectCar = crm_car_selection::where('worklist_id',$request->wl_id)->first();
        if($selectCar->id)
        {   
            $car = avacar::with('complect')->find($selectCar->car_id);
            $array = [
                'base' => $car->complect->price,
                'pack' => $car->packPrice(),
                'dops' => $car->dopprice,
                'total' => $car->totalPrice()
            ];
        }
        echo json_encode($array);
    }
}
