<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_traffic;
use App\crm_client;
use Auth;

class CRMTrafficController extends Controller
{
    public function put(Request $request)
    {
    	if ($request->has('traffic_submit'))
    	{    		
    		$client = new crm_client();
    		$client->name = $request->client_name;
    		$client->birthday = strtotime($request->client_birthday);
    		$client->phone = $request->client_phone;
    		$client->email = $request->client_email;
    		$client->address = $request->client_address;
			$client->save();

    		$traffic = new crm_traffic();
    		$traffic->client_id = $client->id;
    		$traffic->traffic_type_id = $request->traffic_type;
    		$now = strtotime(date('d.m.Y'));
    		$traffic->creation_date = $now;
    		$traffic->manager_id = $request->manager;
    		$traffic->admin_id = Auth::user()->id;
    		$traffic->desired_model = $request->model;
    		$traffic->assigned_action_id = $request->assigned_action;
    		$traffic->comment = $request->comment;
    		$traffic->save();

    		return redirect()->route('crm');
    	}
    }
}
