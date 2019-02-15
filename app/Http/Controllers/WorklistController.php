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
use App\oa_dop;
use App\ava_pack;

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
            }
        }
        else
        {
            $data['dops'] .= '<span class="font-italic">Доп. оборудование не установлено</span>';
        }

        $all_dops = oa_dop::pluck('name', 'id');
        $data['all_dops'] = '';
        foreach ($all_dops as $key => $dop) 
        {
            $data['all_dops'] .= '<span class="col-6"><input type="checkbox" name="wl_dops_check[]" value="'.$key.'"> '.$dop.'</span>';
        }
        
        echo json_encode($data);
    }


    public function getCarByWorklistId(Request $request)
    {
        $car_id = crm_car_selection::where('worklist_id', $request->wl_id)->first()->car_id;

        $car = avacar::find($car_id);

        $data['car_vin'] = $car->vin;
        $data['car_name'] = $car->model->name;
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
            $data['installed'] .= '<li>'.$item->option->name.'</li>';
        }

        $packs = ava_pack::where('avacar_id', $car_id)->get();
        
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

    /* Вывод блока "Автомобиль клиента" */
    public function getOldClientCar(Request $request)
    {
        $oldCar = \App\crm_client_old_car::checkOnOldCar($request->wl_id);
        $res = $oldCar->clientCarHtml();
        echo $res;
    }
}
