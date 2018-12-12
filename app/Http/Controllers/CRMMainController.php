<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_traffic_type;
use App\oa_model;
use App\User;
use App\crm_assigned_action;
use App\crm_traffic;

class CRMMainController extends Controller
{
    public function main()
    {
    	$traffics = crm_traffic::get();

    	$types = crm_traffic_type::get();
    	$models = oa_model::pluck('name', 'id');
    	$users = User::pluck('name', 'id');
    	$assigned_actions = crm_assigned_action::get();
    	
    	return view('crm.main')
    		->with('title', 'CRM "Учет"')
    		->with('traffics', $traffics)
    		->with('traffic_types', $types)
    		->with('models', $models)
    		->with('users', $users)
    		->with('assigned_actions', $assigned_actions);
    }
}
