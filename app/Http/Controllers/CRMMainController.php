<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_traffic_type;
use App\oa_model;
use App\User;
use App\crm_assigned_action;
use App\crm_traffic;
use App\crm_client;

class CRMMainController extends Controller
{
    public function main()
    {
        // Записи Трафика для вкладки "Трафик"
    	$traffics = crm_traffic::get();
        // Записи Клиентов для вкладки "Клиент"
        $clients = crm_client::get();

        // Список типов трафика. Выводится при создании нового трафика 
    	$types = crm_traffic_type::get();
        // Список моделей авто. Выводится при создании нового трафика 
    	$models = oa_model::pluck('name', 'id');
        // Список возможных менеджеров. Выводится при создании нового трафика
        // (на данный момент выводится список всех пользователей системы) 
    	$users = User::pluck('name', 'id');
        // Список возможных назначенных действий. Выводится при создании нового трафика
    	$assigned_actions = crm_assigned_action::get();
    	
    	return view('crm.main')
    		->with('title', 'CRM "Учет"')
    		->with('traffics', $traffics)
    		->with('traffic_types', $types)
    		->with('models', $models)
    		->with('users', $users)
    		->with('assigned_actions', $assigned_actions)
            ->with('clients', $clients);
    }
}
