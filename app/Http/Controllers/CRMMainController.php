<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\crm_traffic_type;
use App\oa_model;
use App\User;
use App\crm_assigned_action;
use App\crm_traffic;
use App\crm_client;
use App\crm_all_field;
use App\crm_setting;
use App\crm_tab;

use App\_tab_client;

class CRMMainController extends Controller
{
    /**
     * Отображение главной (и единственной) страницы CRM 
     */ 
    public function main()
    {
        // Записи Трафика для вкладки "Трафик"
    	$traffics = crm_traffic::get();
        // Записи Клиентов для вкладки "Клиент"
        $clients = crm_client::with('traffic')->get();

        // Список типов трафика. Выводится при создании нового трафика 
    	$types = crm_traffic_type::get();
        // Список моделей авто. Выводится при создании нового трафика 
    	$models = oa_model::pluck('name', 'id');
        // Список возможных менеджеров. Выводится при создании нового трафика
        // (на данный момент выводится список всех пользователей системы) 
    	$users = User::pluck('name', 'id');
        // Список возможных назначенных действий. Выводится при создании нового трафика
    	$assigned_actions = crm_assigned_action::get();

        $fieldlist = crm_all_field::get();

        $clientTab = _tab_client::with('model')->with('manager')->with('action')->paginate(2);

        $test = DB::table('crm_tabs')
            ->join('crm_all_fields', 'crm_tabs.field_id', '=', 'crm_all_fields.id')
            ->join('crm_settings', 'crm_tabs.setting_id', '=', 'crm_settings.id')
            ->select('crm_settings.id as s_id', 'crm_all_fields.id as f_id', 'crm_settings.name as setting_name', 'crm_settings.level', 'crm_settings.field', 'crm_settings.active', 'crm_all_fields.name as field_name')
            ->get();
    	
    	return view('crm.main')
    		->with('title', 'CRM "Учет"')
    		->with('traffics', $traffics)
    		->with('traffic_types', $types)
    		->with('models', $models)
    		->with('users', $users)
    		->with('assigned_actions', $assigned_actions)
            ->with('clients', $clients)
            ->with('fieldlist', $fieldlist)
            ->with('test', $test)
            ->with('clientTab',$clientTab);
    }


    /**
     * Сохранение новой настройки (добавление в БД) в модальном окне Настроек отображения полей
     */
    public function saveSetting(Request $request)
    {
        if (isset($request->saveSettings))
        {
            $settings = new crm_setting();
            $settings->name = $request->settingName;
            $settings->level = $request->settingLevel;
            $settings->field = $request->field;
            $settings->save();

            foreach($request->fieldsCheckbox as $field)
            {
                $tabs = new crm_tab();
                $tabs->setting_id = $settings->id;
                $tabs->field_id = $field;
                $tabs->save();
            }
        }
        
        return redirect()->route('crm');
    }

    /**
     * Сделать выбранную настройку отображения полей в текущей вкладке активной
     */
    public function setActive(Request $request, $id)
    {
        // Берем название вкладки по id
        $field = crm_setting::where('id', $id)->first()->field;
        // В текущей вкладке обнуляем активные настройки, а потом делаем активной ту, что выбрали, по id
        crm_setting::where('field', $field)->update(['active' => 0]);
        crm_setting::where('id', $id)->update(['active' => 1]);

        return redirect()->route('crm');
    }
}
