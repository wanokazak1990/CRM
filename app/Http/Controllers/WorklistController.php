<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_traffic;
use App\crm_worklist;
use App\crm_client;
use App\crm_client_contact;
use App\crm_testdrive;
use App\crm_car_selection;
use App\avacar;
use App\crm_all_field;
use App\oa_dop;
use App\ava_pack;
use App\crm_need_car;
use App\ava_dop;
use App\company;
use App\crm_worklist_company;
use App\crm_offered_dop;

use App\crm_configurator;
use App\crm_commercial_offer;
use App\crm_ofu_product;

use App\crm_client_pay;
use App\crm_worklist_contract;
use App\crm_contract_ship;

use Storage;

class WorklistController extends Controller
{
    /**
     * Добавить Рабочий лист
     */
    public function add(Request $request,$data=array())
    {
    	$traffic = crm_traffic::find($request->traffic_id);
    	$client = crm_client::find($traffic->client_id);

    	$worklist = new crm_worklist();
    	$worklist->traffic_id = $traffic->id;
    	$worklist->client_id = $traffic->client_id;
    	$worklist->manager_id = $request->manager_id;
    	$worklist->save();
    	
    	$data['wl_id'] = $worklist->id;
    	$data['wl_addingday'] = $worklist->created_at->format('d.m.Y');
    	$data['traffic_type'] = @$traffic->traffic_type->name;
    	$data['traffic_model'] = @$traffic->model->name;
    	$data['wl_manager'] = @$worklist->manager->name;
    	$data['client_area'] = @$client->area->id;
    	$data['client_name'] = @$client->name;
    	$data['client_lastname'] = @$client->lastname;
    	$data['client_secondname'] = @$client->secondname;
    	$data['client_type'] = @$client->type->id;

    	$data['contacts'] = array();
    	$contacts = @$client->contacts;
    	foreach ($contacts as $key => $value) {
    		$data['contacts'][] = array(
    			'contact_phone'=>$value->phone,
    			'contact_email'=>$value->email,
    			'contact_marker'=>$value->marker
    		);
    	}

    	$data['traffic_action'] = $traffic->assigned_action->id;
    	$data['traffic_action_date'] = date('d.m.Y',$traffic->action_date );
    	$data['traffic_action_time'] = date('H:i',$traffic->action_time );
    	 	
    	echo json_encode($data);
    }

    /**
     * Сохранить изменения в Рабочем листе
     */
    public function saveChanges(Request $request)
    {   

        /*$string = json_decode($request->worksheet);
        parse_str($string, $data);*/
        //print_r($request->all());
        $worklist = crm_worklist::find($request->wl_id);
        $traffic = crm_traffic::find($worklist->traffic_id);
        $client = crm_client::find($worklist->client_id);

        $traffic->assigned_action_id = $request->traffic_action;
        $traffic->action_date = strtotime($request->traffic_action_date);
        $traffic->action_time = strtotime($request->traffic_action_time);
        $traffic->save();

        $client->name = $request->client_name;
        $client->secondname = $request->client_secondname;
        $client->lastname = $request->client_lastname;
        $client->type_id = $request->client_type;
        $client->area_id = $request->client_area;
        $client->address = $request->client_address;
        $client->birthday = strtotime($request->client_birthday);
        $client->passport_serial = $request->client_passport_serial;
        $client->passport_number = $request->client_passport_number;
        $client->passport_giveday = strtotime($request->client_passport_giveday);
        $client->drive_number = $request->client_drive_number;
        $client->drive_giveday = strtotime($request->client_drive_giveday);
        $client->save();

        crm_client_contact::where('client_id', $worklist->client_id)->delete();

        foreach ($request->contact_phone as $key => $value) 
        {
            $contacts = new crm_client_contact();
            $contacts->client_id = $worklist->client_id;
            $contacts->phone = $value;
            $contacts->email = $request->contact_email[$key];
            $contacts->marker = $request->contact_marker[$key];
            $contacts->save();
        }
        
        if($request->has('cc'))//Если указаны данные для старого авто клиента
        {
            //проверям есть ли бу авто за этим раб.листом
            //елси есть то возвращаем объект с данными об бу авто
            //если авто не было то создаём пустой объект
            $oldCar = \App\crm_client_old_car::checkOnOldCar($worklist->id);
            $oldCar->saveOldCar($request->cc,$worklist);
        }

        //удалить все компании выбранные для рл до этого момента
        crm_worklist_company::where('wl_id',$worklist->id)->delete();
        if($request->has('loyalty'))//если выбрана хотябы одна компания
        {
            foreach ($request->loyalty['company_id'] as $key => $item) //то проходимся по массиву из id компаний
            {
                $wl_company = new crm_worklist_company([
                    'wl_id'=>$worklist->id,
                    'company_id'=>$item,
                    'sum'=>str_replace(' ', '', $request->loyalty['sum'][$item]),
                    'rep'=>str_replace(' ', '', $request->loyalty['rep'][$item]),
                    'razdel'=>$request->loyalty['razdel'][$item]
                ]);
                $wl_company->save();
            }
        }

        // Сохранение отмеченного оборудования из блока "Дополнительное оборудование" в РЛ
        if ($request->has('wl_dops_check'))
        {
            crm_offered_dop::where('worklist_id', $request->wl_id)->delete();
            $dops = new crm_offered_dop();
            $dops->worklist_id = $request->wl_id;
            $dops->options = json_encode($request->wl_dops_check);
            if ($request->has('wl_dops_offered'))
                $dops->price = $request->wl_dops_offered;
            $dops->save();
        }


        // Сохранение данных автомобилей из блока "Конфигуратор" в РЛ
        if ($request->has('cfg_cars'))
        {
            crm_configurator::where('worklist_id', $request->wl_id)->delete();

            $cfg_cars = json_decode($request->cfg_cars);
            foreach ($cfg_cars as $key => $car) 
            {
                $cfg = new crm_configurator();
                $cfg->worklist_id = $request->wl_id;
                $cfg->model_id = $car->cfg_model;
                $cfg->complect_id = $car->cfg_complect;
                $cfg->color_id = $car->cfg_color_id;
                $cfg->options = json_encode($car->options);
                $cfg->save();
            }
        }

        // Сохранение продуктов ОФУ
        crm_ofu_product::where('worklist_id', $request->wl_id)->delete();
        if ($request->has('ofu_products'))
        {
            $ofu_products = json_decode($request->ofu_products);
            foreach ($ofu_products as $key => $ofu_product) 
            {
                $product = new crm_ofu_product();
                $product->worklist_id = $request->wl_id;
                $product->author_id = $ofu_product->author;
                $product->product_id = $ofu_product->product;
                $product->partner_id = $ofu_product->partner;
                $product->price = $ofu_product->price;
                $product->creation_date = strtotime($ofu_product->creation_date);
                $product->end_date = strtotime($ofu_product->end_date);
                $product->profit = $ofu_product->profit;
                $product->profit_date = strtotime($ofu_product->profit_date);
                $product->save();
            }
        }

        //СОХРАНЕНИЕ ВКЛАДКИ ОФОРМЛЕНИЕ -> ПЛАТЕЖИ
        crm_client_pay::where('worklist_id',$request->wl_id)->delete();
        if($request->has('wl_pay_sum'))
        {
            foreach ($request->wl_pay_sum as $key => $value) {
                if($request->wl_pay_sum[$key] && $request->wl_pay_date[$key] && $request->wl_pay_debt[$key])
                    $id = crm_client_pay::create([
                        'worklist_id'=>$worklist->id,
                        'client_id'=>$worklist->client_id,
                        'pay'=>$request->wl_pay_sum[$key],
                        'date'=>strtotime($request->wl_pay_date[$key]),
                        'debt'=>$request->wl_pay_debt[$key],
                        'status'=>@$request->wl_pay_status[$key]
                    ]);
            }
        }


        //СОХРАНЕНИЕ ВКЛАДКИ ОФОРМЛЕНИЕ -> ОФОРМЛЕНИЕ
        $oldContract = crm_worklist_contract::where('worklist_id',$request->wl_id)->first();
        $contractId = isset($oldContract->id)?$oldContract->id:'';
        if($contractId)
            crm_worklist_contract::where('id',$contractId)->delete();
        if($request->has('contract'))
        {
            if($request->contract['author_id'])
            {
                $data = $request->contract;
                $data['worklist_id'] = $worklist->id;
                $data['client_id'] = $worklist->client_id;
                foreach ($data as $key => $value) {
                    if(substr_count($key,"date"))
                        $data[$key] = strtotime($value);
                }
                $res = crm_worklist_contract::create($data);
                if($res)
                    if($data['ship'])
                        crm_contract_ship::where('contract_id',$contractId)->delete();
                        foreach ($data['ship'] as $key => $value) {
                            if($value)
                                crm_contract_ship::create(['contract_id'=>$res->id,'date'=>strtotime($value)]);
                        }
            }
        }
        echo 1;

    }

    /**
     * Загрузить данные из БД в Рабочий лист
     */
    public function loadData(Request $request, $data=array())
    {
        $worklist = crm_worklist::find($request->wl_id);
        $client = crm_client::find($worklist->client_id);
        $traffic = crm_traffic::find($worklist->traffic_id);

        $data['wl_id'] = $request->wl_id;
        $data['wl_addingday'] = $worklist->created_at->format('d.m.Y');

        $data['traffic_type'] = @$traffic->traffic_type->name;
        $data['traffic_model'] = @$traffic->model->name;
        $data['wl_manager'] = @$worklist->manager->name;
        $data['traffic_action'] = @$traffic->assigned_action_id;
        $data['traffic_action_date'] = date('d.m.Y',$traffic->action_date );
        $data['traffic_action_time'] = date('H:i',$traffic->action_time );

        $data['client_type'] = @$client->type->id;
        $data['client_name'] = @$client->name;
        $data['client_secondname'] = @$client->secondname;
        $data['client_lastname'] = @$client->lastname;

        $data['client_area'] = @$client->area->id;
        $data['client_address'] = @$client->address;
        $data['client_birthday'] = date('d.m.Y',$client->birthday);

        $data['client_passport_serial'] = @$client->passport_serial; 
        $data['client_passport_number'] = @$client->passport_number; 
        $data['client_passport_giveday'] = @date('d.m.Y', $client->passport_giveday);

        $data['client_drive_number'] = @$client->drive_number;
        $data['client_drive_giveday'] = @date('d.m.Y', $client->drive_giveday);

        $data['contacts'] = array();
        
        $contacts = @$client->contacts;
        foreach ($contacts as $key => $value) {
            $data['contacts'][] = array(
                'contact_phone'=>$value->phone,
                'contact_email'=>$value->email,
                'contact_marker'=>$value->marker
            );
        }

        echo json_encode($data);
    }


    /**
     * Добавить машину в Пробную поездку
     */
    public function addTestDrive(Request $request)
    {
        $worklist = crm_worklist::find($request->wl_id);

        $testdrive = new crm_testdrive();
        $testdrive->client_id = $worklist->client_id;
        $testdrive->worklist_id = $request->wl_id;
        $testdrive->model_id = $request->model_id;
        $testdrive->date = strtotime(date('d.m.Y'));
        $testdrive->time = strtotime(date('H:i:s'));
        $testdrive->status = 0;
        $testdrive->save();

        $html = self::getTestDriveHTML($request->wl_id);

        echo json_encode($html);
    }


    /**
     * Вывести машины в Пробной поездке
     */
    public function loadTestDrive(Request $request)
    {
        $html = self::getTestDriveHTML($request->wl_id);

        echo json_encode($html);
    }


    /**
     * Удалить машину из Пробной поездки и вывести обновленный список машин
     */
    public function deleteTestDrive(Request $request)
    {
        $wl_id = crm_testdrive::select('worklist_id')->find($request->testdrive_id)->worklist_id;
        crm_testdrive::where('id', $request->testdrive_id)->delete();

        $html = self::getTestDriveHTML($wl_id);

        echo json_encode($html);
    }


    /**
     * Резервировать машину в Пробной поездке (Кнопка "Резервировать")
     */
    public function reserveCar(Request $request)
    {
        $client = crm_worklist::find($request->wl_id)->client_id;

        $car = new crm_car_selection();
        $car->client_id = $client;
        $car->worklist_id = $request->wl_id;
        $car->car_id = $request->car_id;
        $car->save();
        
        echo json_encode('OK');
    }


    /**
     * Получить и вывести список установленного оборудования, цену установленного оборудования
     * и список оборудования для предложения
     */
    public function getDops(Request $request)
    {
        $car_id = crm_car_selection::where('worklist_id', $request->wl_id)->first()->car_id;

        $car = avacar::find($car_id);

        if ($car->dopprice == null)
            $data['dopprice'] = 0;
        else
            $data['dopprice'] = $car->dopprice;

        $data['dops'] = '';
        if (count($car->dops) > 0)
        {
            foreach ($car->dops as $key => $value) 
            {
                $data['dops'] .= '<span class="col-3"><input type="checkbox" checked disabled> '.$value->dop->name.'</span>';
                $notIn[] = $value->dop_id;
            }
        }
        else
        {
            $data['dops'] .= '<span class="font-italic">Доп. оборудование не установлено</span>';
        }

        if (isset($notIn))
            $all_dops = oa_dop::whereNotIn('id', $notIn)->pluck('name', 'id');
        else
            $all_dops = oa_dop::pluck('name', 'id');

        $data['all_dops'] = '';
        foreach ($all_dops as $key => $dop) 
        {
            $data['all_dops'] .= '<span class="col-6"><input type="checkbox" name="wl_dops_check[]" value="'.$key.'"> '.$dop.'</span>';
        }

        $offered_dops = crm_offered_dop::where('worklist_id', $request->wl_id)->first();
        if ($offered_dops != null)
        {
            $data['offered_dops'] = json_decode($offered_dops->options, true);
            if ($offered_dops->price != null)
                $data['offered_price'] = $offered_dops->price;
        }
        
        echo json_encode($data);
    }


    public function installDops(Request $request)
    {
        crm_offered_dop::where('worklist_id', $request->wl_id)->delete();
        
        $car_id = crm_car_selection::where('worklist_id', $request->wl_id)->first()->car_id;

        $car = avacar::find($car_id);
        $car->dopprice = $car->dopprice + (int)$request->wl_dops_sum;
        $car->save();

        $dops = explode(',', $request->wl_dops);
        foreach ($dops as $key => $dop_id)
        {
            $ava_dop = new ava_dop();
            $ava_dop->avacar_id = $car_id;
            $ava_dop->dop_id = $dop_id;
            $ava_dop->save();
        }
        
        echo json_encode('done');
    }

    /**
     * Получить архив коммерческих предложений
     *
     */
    public function getOffersList(Request $request)
    {
        $offers = crm_commercial_offer::where('worklist_id', $request->wl_id)->orderBy('id', 'desc')->get();

        if (count($offers) != 0)
        {
            foreach ($offers as $offer_key => $offer) 
            {
                $car_vin = [];
                
                if ($offer->cars_ids != null)
                {
                    foreach(explode(',', $offer->cars_ids) as $key => $car_id)
                    {
                        $car_vin[] = avacar::find($car_id)->vin;
                    }
                }

                if ($offer->cfg_cars != null)
                {
                    $car_vin[] = 'Конфигуратор (' . count(explode(',', $offer->cfg_cars)) . ' шт.)';
                }

                if ($offer->cars_ids == null && $offer->cfg_cars == null)
                {
                    $car_id = crm_car_selection::where('worklist_id', $request->wl_id)->first()->car_id;

                    $car_vin[] = avacar::find($car_id)->vin;
                }


                $data[] = array(
                    'creation_date' => date('d.m.Y H:i', $offer->creation_date),
                    'vins' => implode(', ', $car_vin),
                    'offer_id' => $offer->id
                );
            }

            echo json_encode($data);
        }
        else
            echo '0';
    }

    /**
     * Получение данных об автомобиле, привязанном к рабочему листу
     * return - массив данных для вкладки Автомобиль в Рабочем листе
     */
    public function getCarByWorklistId(Request $request)
    {
        $selected_car = crm_car_selection::where('worklist_id', $request->wl_id)->first();

        if ($selected_car == null)
            echo json_encode('0');
        else
        {
            $car = avacar::find($selected_car->car_id);
            $sale = crm_worklist_company::with('company')->where('wl_id', $request->wl_id)->where('razdel', '<>', 3)->get();

            $data['car_id'] = $car->id;
            $data['car_vin'] = $car->vin;
            $data['car_name'] = $car->brand->name.' '.$car->model->name;
            $data['complect_code'] = $car->complect->code;
            $data['complect_name'] = $car->complect->name;
            $data['complect_price'] = $car->complect->price;
            $data['img'] = '/storage/images/'.$car->model->link.'/'.$car->model->alpha;
            $data['color_code'] = $car->color->web_code;
            $data['color_rn_code'] = $car->color->rn_code;
            $data['color_name'] = $car->color->name;
            $data['car_info'] = '
                <li>Двигатель '.$car->complect->motor->type->name." ".$car->complect->motor->valve.'-клапанный</li>
                <li>Рабочий объем '.$car->complect->motor->size.'л. ('.$car->complect->motor->power.'л.с.)</li>
                <li>КПП '.$car->complect->motor->transmission->name.'</li>
                <li>Привод '.$car->complect->motor->wheel->name.'</li>';

            $data['car_sale'] = $sale->filter(function($item){
                if ($item->razdel == 1 || $item->razdel == 4)
                    return $item;
            })->sum('sum');

            $data['dop_sale'] = $sale->filter(function($item){
                if ($item->razdel == 2)
                    return $item;
            })->sum('sum');

            $data['company'] = $sale;

            $data['dops'] = '';
            if (count($car->dops) > 0)
            {
                foreach ($car->dops as $key => $value) 
                {
                    $data['dops'] .= '<li>'.$value->dop->name.'</li>';
                }
            }
            else
            {
                $data['dops'] .= '<li>Доп. оборудование не установлено</li>';
            }

            if ($car->dopprice == null)
                $data['car_dopprice'] = 0;
            else
                $data['car_dopprice'] = $car->dopprice;

            $data['installed'] = '';
            foreach ($car->complect->installoption as $key => $item)
            {
                $data['installed'] .= '<li>- '.$item->option->name.'</li>';
            }

            $packs = ava_pack::where('avacar_id', $car->id)->get();
            
            $data['fullprice'] = (int)$data['car_dopprice'] + (int)$data['complect_price'];

            $data['options'] = '';
            foreach ($packs as $key => $value) 
            {
                $data['options'] .= '<div class="input-group text-secondary no-gutters">
                <div class="col-12 border-bottom">'.$value->pack->name.'<div>';

                foreach ($value->pack->option as $k => $val) 
                {
                    $data['options'] .= $val->option->name;
                }

                $data['options'] .= '</div>
                    </div>
                    <div class="col-12 d-flex">
                        <div class="flex-grow-1">'
                        .$value->pack->code.  
                        '</div>
                        <div class="h5">'.$value->pack->price.'</div>
                    </div>
                </div>';

                $data['fullprice'] = $data['fullprice'] + (int)$value->pack->price;
            }

            echo json_encode($data);
        }
        
    } 


    public function removeReserved(Request $request)
    {
        crm_car_selection::where('worklist_id', $request->wl_id)->delete();

        echo json_encode('done');
    }


    /**
     * Функция отрисовки блоков машин для Пробной поездки
     * $worklist - id рабочего листа
     * return - html код блоков машин
     */
    public function getTestDriveHTML($worklist)
    {
        $testdrives = crm_testdrive::where('worklist_id', $worklist)->get();

        $html = '';
        foreach ($testdrives as $key => $car)
        {
            $html .= '
            <div class="col-3 border">
                <div class="text-right">
                    <a href="#" class="wl_del_testdrive" id="'.$car->id.'"><i class="fas fa-trash-alt text-danger"></i></a>
                </div>
                <input type="text" class="form-control text-center" value="'.$car->model->name.'" disabled>
                <div class="d-flex justify-content-center">
                    <p align="center">
                        '.date('d.m.Y', $car->date).' в '.date('H:i', $car->time).'
                        <br>'; 
                        if($car->status == 0) 
                            $html .= '<span class="text-danger">В обработке</span>'; 
                        else 
                            $html .= '<span class="text-success">Оценка '.$car->status.'</span>'; 
            $html .= '</p>
                </div>
            </div>';
        }

        return $html;
    }


    /**
     * Получить блоки машин в Подборе по потребностям
     */
    public function getNeedCars(Request $request)
    {
        $data['blocks'] = crm_need_car::getCarBlocks($request->wl_id);
        $data['options'] = crm_need_car::getCarOptions($request->wl_id);
        $data['purchase_type'] = crm_need_car::getPurchaseType($request->wl_id);
        $data['pay_type'] = crm_need_car::getPayType($request->wl_id);
        $data['firstpay'] = crm_need_car::getFirstPay($request->wl_id);

        echo json_encode($data);
    }

    /**
     * Сохранить выбранные машины в БД в Подборе по потребностям
     */
    public function saveNeedCars(Request $request)
    {
        crm_need_car::where('worklist_id', $request->wl_id)->delete();

        $needcars = json_decode($request->data);

        foreach ($needcars as $key => $val) 
        {
            $car = new crm_need_car();
            $car->worklist_id = $request->wl_id;
            $car->model_id = $val->model;
            $car->wheel_id = $val->wheel;
            $car->transmission_id = $val->transmission;
            $car->purchase_type = $request->purchase_type;
            $car->pay_type = $request->pay_type;
            $car->firstpay = $request->firstpay;
            if ($request->wl_need_option != null)
                $car->options = json_encode($request->wl_need_option);
            $car->save();
        }

        $data['blocks'] = crm_need_car::getCarBlocks($request->wl_id);
        $data['options'] = crm_need_car::getCarOptions($request->wl_id);

        echo json_encode($data);
    }

    /**
     * Получить сохраненные машины из Конфигуратора в РЛ
     */
    public function getCfgCars(Request $request)
    {
        $cfg_cars = crm_configurator::where('worklist_id', $request->wl_id)->get();
        echo json_encode($cfg_cars);
    }

    /**
     * Получить количество машин в автоскладе по модели и комплектации из конфигуратора
     */
    public function carCountInStock(Request $request)
    {
        $count = avacar::with('getAuthor')
            ->with('getDateOrder')
            ->with('getDatePlanned')
            ->with('getDateBuild')
            ->with('getDateReady')
            ->with('brand')
            ->with('model')
            ->with('complect')
            ->with('color')
            ->where('model_id', $request->model_id)
            ->where('complect_id', $request->complect_id)
            ->orderBy('id','DESC')
            ->count();
        
        echo $count;
    }

    /**
     * Показать машины в автоскладе по модели и комплектации из конфигуратора
     */
    public function showCfgCars(Request $request)
    {
        $list = avacar::with('getAuthor')
            ->with('getDateOrder')
            ->with('getDatePlanned')
            ->with('getDateBuild')
            ->with('getDateReady')
            ->with('brand')
            ->with('model')
            ->with('complect')
            ->with('color')
            ->where('model_id', $request->model_id)
            ->where('complect_id', $request->complect_id)
            ->get();

        $tr = new \App\autostock_helper($list);
        $tr->makeTableData();

        $titles = crm_all_field::where('type_id',3)->pluck('name','id')->toArray();

        $links = '';
        echo json_encode([
            'list'=>$tr->response,
            'links'=>$links,
            'titles'=>array_merge(['&nbsp'],$titles)
        ]);
    }

    /**
     * Проверить, есть ли у Рабочего листа зарезервированный автомобиль
     */
    public function checkSelectedCar(Request $request)
    {
        $cars = crm_car_selection::where('worklist_id', $request->worklist_id)->get();
        if (count($cars) == 1)
            echo '1';
        else
            echo '0';
    }

    /**
     * Конфигуратор
     * Кнопка "Создать заявку"
     * Создает автомобиль и резервирует его за РЛ
     */
    public function cfgCreateRequest(Request $request)
    {
        $worklist_id = $request->wl_id;

        if ($request->has('cfg_car'))
        {
            $car = json_decode($request->cfg_car);

            $cfg = new crm_configurator();
            $cfg->worklist_id = $worklist_id;
            $cfg->model_id = $car->cfg_model;
            $cfg->complect_id = $car->cfg_complect;
            $cfg->color_id = $car->cfg_color_id;
            $cfg->options = json_encode($car->options);
            $cfg->save();            

            $find_cfg = crm_configurator::find($cfg->id);
        }
        elseif ($request->has('cfg_id'))
        {
            $find_cfg = crm_configurator::find($request->cfg_id);
        }

        $avacar = new avacar();
        $avacar->model_id = $find_cfg->model_id;
        $avacar->brand_id = $find_cfg->model->brand->id;
        $avacar->complect_id = $find_cfg->complect_id;
        if ($find_cfg->color_id != null)
            $avacar->color_id = $find_cfg->color_id;
        $avacar->save();

        if($find_cfg->options != null)
        {
            foreach(json_decode($find_cfg->options) as $key => $val)
            {
                $ava_pack = new ava_pack();
                $ava_pack->avacar_id = $avacar->id;
                $ava_pack->pack_id = $val;
                $ava_pack->save();
            }
        }

        $client_id = crm_worklist::find($worklist_id)->client_id;
        $selected_car = new crm_car_selection();
        $selected_car->client_id = $client_id;
        $selected_car->worklist_id = $worklist_id;
        $selected_car->car_id = $avacar->id;
        $selected_car->save();

        echo json_encode('done');
    }


    /**
     * Вкладка "Оформление"
     * Продукты ОФУ
     * Получить список сервисов
     */
    public function getServiceList(Request $request)
    {
        $services = company::where('razdel', 3)->get();
        if (count($services) != 0)
            echo $services;
        else
            echo '0';
    }
    
    /**
     * Вкладка "Оформление"
     * Продукты ОФУ
     * Получить список ВЫБРАННЫХ сервисов
     */
    public function getWorklistServices(Request $request)
    {
        $services = crm_worklist_company::where('wl_id', $request->wl_id)->get();

        if (count($services) > 0)
            echo json_encode($services);
        else
            echo json_encode('0');
    }

    /**
     * Вкладка "Оформление"
     * Продукты ОФУ
     * Получить сохраненные блоки с Продуктами
     */
    public function getOfuBlocks(Request $request)
    {
        $blocks = crm_ofu_product::getProductBlock($request->wl_id);
        
        if ($blocks == null)
            echo json_encode('0');
        else
            echo json_encode($blocks);
    }

    /**
     * Вкладка "Оформление"
     * Продукты ОФУ
     * Добавление нового блока продукта для заполнения
     */
    public function ofuAddBlock(Request $request)
    {
        $block = crm_ofu_product::getProductBlock();

        echo $block;
    }


    /* Вывод блока "Автомобиль клиента" */
    public function getOldClientCar(Request $request)
    {
        $oldCar = \App\crm_client_old_car::checkOnOldCar($request->wl_id);
        $res = $oldCar->clientCarHtml();
        echo $res;
    }

    public function addoldcarphoto(Request $request,$mas = array())
    {   
        if($request->has('wl_id'))
        {
            foreach ($request->file() as $key=>$file) {
                $name = $file->getClientOriginalName().'.'.pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $str = (string)$file->move(storage_path('app/public/worklist/'.$request->wl_id.'/oldcar'), $name);
                $str = str_replace('/home/it/www/new1.loc', '', $str);
                $str = str_replace('app/public/', '', $str);
                
                $mas[] = $str;
            } 
        }
        echo json_encode($mas);
    }

    public function getLoyaltyProgram(Request $request)//получить подходящие программы лояльности, для выбранного авто
    {
        if(!$request->has('wl_id')) return;//если нет ид ворклиста уходим
        $selectionCar = crm_car_selection::where('worklist_id',$request->wl_id)->first();//выбранная машина
        if(isset($selectionCar->car_id) && !empty($selectionCar->car_id))//если есть выбранная мащина
        {
            $company = company::clientCompanyForHtml($selectionCar);
            echo json_encode($company);
        }
    }

    public function getPays(Request $request)
    {   
        $carSale = 0;
        $carPrice = 0;
        if($request->has('worklist_id')) :
            $worklist = crm_worklist::with('pays')->with('selectedCompanies')->find($request->worklist_id);
            $selectionCar = crm_car_selection::with('avacar')->where('worklist_id',$request->worklist_id)->first();
            if(!empty($worklist->selectedCompanies)) :
                foreach ($worklist->selectedCompanies as $key => $value) :
                    if(in_array($value->razdel, [1,2,4])) :
                        $carSale += $value->sum;
                    endif;
                endforeach;
            endif;

            if(!empty($selectionCar->avacar)):
                $carPrice = $selectionCar->avacar->totalPrice();
            endif;
        endif;
        echo (\App\crm_client_pay::getHtml($worklist->pays,$carPrice-$carSale));
    }

    public function getContracts(Request $request)
    {
        

        if($request->has('worklist_id'))
            $contract = crm_worklist_contract::where('worklist_id',$request->worklist_id)->first();
        if(!$contract)
            $contract= new crm_worklist_contract();

        echo $contract->getHtml();
    }
}
