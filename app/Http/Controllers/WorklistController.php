<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_traffic;
use App\crm_worklist;
use App\crm_client;

class WorklistController extends Controller
{
    //
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
}
