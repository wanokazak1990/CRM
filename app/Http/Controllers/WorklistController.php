<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_traffic;
use App\crm_worklist;
use App\crm_client;
use App\crm_client_contact;
use App\crm_testdrive;

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
        $string = json_decode($request->worksheet);
        parse_str($string, $data);

        $worklist = crm_worklist::find($data['wl_id']);
        $traffic = crm_traffic::find($worklist->traffic_id);
        $client = crm_client::find($worklist->client_id);

        $traffic->assigned_action_id = $data['traffic_action'];
        $traffic->action_date = strtotime($data['traffic_action_date']);
        $traffic->action_time = strtotime($data['traffic_action_time']);
        $traffic->save();

        $client->name = $data['client_name'];
        $client->secondname = $data['client_secondname'];
        $client->lastname = $data['client_lastname'];
        $client->type_id = $data['client_type'];
        $client->area_id = $data['client_area'];
        $client->address = $data['client_address'];
        $client->birthday = strtotime($data['client_birthday']);
        $client->passport_serial = $data['client_passport_serial'];
        $client->passport_number = $data['client_passport_number'];
        $client->passport_giveday = strtotime($data['client_passport_giveday']);
        $client->drive_number = $data['client_drive_number'];
        $client->drive_giveday = strtotime($data['client_drive_giveday']);
        $client->save();

        crm_client_contact::where('client_id', $worklist->client_id)->delete();

        foreach ($data['contact_phone'] as $key => $value) 
        {
            $contacts = new crm_client_contact();
            $contacts->client_id = $worklist->client_id;
            $contacts->phone = $value;
            $contacts->email = $data['contact_email'][$key];
            $contacts->marker = $data['contact_marker'][$key];
            $contacts->save();
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
}
