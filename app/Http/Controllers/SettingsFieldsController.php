<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_all_field as fields;
use App\crm_setting as setting;
use App\crm_tab as tab;

class SettingsFieldsController extends Controller
{
    public function get(Request $request)
    {
    	$base = setting::where('id',$request->settings_id)->first();
    	
    	$list = tab::select('field_id')->where('setting_id',$request->settings_id)->get();
    	$array = ['type'=>$base->field,'list'=>$list->toArray()];
    	echo json_encode($array);
    }
}
