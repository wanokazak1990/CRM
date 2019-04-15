<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_traffic;
use App\crm_client;
use Auth;
use App\_tab_traffic;
use App\crm_client_contact;
use App\crm_worklist;

class CRMTrafficController extends Controller
{
    public function put(Request $request)
    {       
    		$client = new crm_client();
    		$client->name = $request->client_name;
    		$client->lastname = $request->client_lastname;
    		$client->secondname = $request->client_secondname;
            $client->area_id = $request->area_id;
			$client->save();

            $contact = new crm_client_contact();
            $contact->client_id = $client->id;
            $contact->phone = $request->client_phone;
            $contact->email = $request->client_email;
            $contact->save();

    		$traffic = new crm_traffic();
    		$traffic->client_id = $client->id;
    		$traffic->traffic_type_id = $request->traffic_type;
    		$now = strtotime(date('d.m.Y H:i:s'));
    		$traffic->creation_date = $now;
    		$traffic->manager_id = $request->manager;
    		$traffic->admin_id = Auth::user()->id;
    		$traffic->desired_model = $request->model;
    		$traffic->assigned_action_id = $request->assigned_action;
    		$traffic->action_date = ($request->has('action_date') && !empty($request->action_date))?
                strtotime($request->action_date)
                :strtotime(date('d.m.Y'));
    		$traffic->action_time = ($request->has('action_time') && !empty($request->action_time))?
                strtotime($request->action_time):
                strtotime(date('H:i'));
    		$traffic->comment = $request->comment;
    		$traffic->save();

    		if($traffic->id)
            {
                $data['status'] = 1; //статус что всё хорошо
                $data['data']['user'] = $traffic->manager_id; //кому отправлять
                $data['data']['client'] = $client->name; //имя клиента
                $data['data']['traffic_id'] = $traffic->id; //ид нового трафика
                echo json_encode($data);
            }
            else
               $data['status'] = 0;
            return;
    }

    public function get(Request $request)
    {
        if($request->has('id'))
        {
            $traffic =  _tab_traffic::with('model')->with('manager')->with('action')->with('admin')->find($request->id);
            $data = array(
                'id'        =>      $traffic->id,
                'date'      =>      date('H:i',$traffic->creation_date),
                'author'    =>      @$traffic->admin->name,
                'type'      =>      @$traffic->trafic_type->name,
                'model'     =>      @$traffic->model->name,
                'address'   =>      @$traffic->address,
                'action'    =>      @$traffic->action->name,
                'client'    =>      $traffic->name,
                'manager'   =>      Auth::user()->name
            );
            echo json_encode($data);
        }
    }

    /**
     * Получение типа трафика, интересующей модели и зоны контакта
     * для модального окна Редактирования трафика
     */
    public function getWorklistInfo(Request $request)
    {
        $traffic = crm_worklist::find($request->wl_id)->traffic;

        $data['type'] = $traffic->traffic_type_id;
        $data['model'] = $traffic->desired_model;
        $data['area'] = $traffic->client->area_id;

        echo json_encode($data);
    }

    /**
     * Обновление данных трафика из модального окна Редактирования трафика
     */
    public function updateWorklistInfo(Request $request)
    {
        if ($request->has('wl_id'))
        {
            $traffic_id = crm_worklist::find($request->wl_id)->traffic_id;
            $client_id = crm_worklist::find($request->wl_id)->client_id;

            $traffic = crm_traffic::find($traffic_id);
            $traffic->traffic_type_id = $request->type;
            $traffic->desired_model = $request->model;
            $traffic->save();

            $client = crm_client::find($client_id);
            $client->area_id = $request->area;
            $client->save();

            echo json_encode("1");
        }
        else
            echo json_encode("0");
    }
}
