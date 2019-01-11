<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_traffic;
use App\crm_client;
use Auth;
use App\_tab_traffic;

class CRMTrafficController extends Controller
{
    public function put(Request $request)
    {
    		$client = new crm_client();
    		$client->name = $request->client_name;
    		$client->phone = $request->client_phone;
    		$client->email = $request->client_email;
    		$client->address = $request->client_address;
			$client->save();

    		$traffic = new crm_traffic();
    		$traffic->client_id = $client->id;
    		$traffic->traffic_type_id = $request->traffic_type;
    		$now = strtotime(date('d.m.Y H:i:s'));
    		$traffic->creation_date = $now;
    		$traffic->manager_id = $request->manager;
    		$traffic->admin_id = Auth::user()->id;
    		$traffic->desired_model = $request->model;
    		$traffic->assigned_action_id = $request->assigned_action;
    		$traffic->action_date = strtotime($request->action_date);
    		$traffic->action_time = strtotime($request->action_time);
    		$traffic->comment = $request->comment;
    		$traffic->save();

    		if($traffic->id)
            {
                $data['status'] = 1;
                $data['user'] = $traffic->manager_id;
                $data['message'] = "
                    <a class='alert_traffic' traffic_id=' {$traffic->id} '>
                       {$client->name} 
                    </a>
                ";
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
}
