<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_all_field;
use App\crm_setting;

class CRMAjaxController extends Controller
{
    /**
	 * Получить названия полей для текущей вкладки CRM
	 * Вывести их в модальное окно настроек отображения полей
	 * tab_id - ID текущей вкладки
     */
    public function getCurrentFields()
    {
    	if (isset($_POST['tab_id']))
    	{
    		switch ($_POST['tab_id']) {
    			case 'clients':
    				$val = 2;
    				break;
    			case 'traffic':
    				$val = 1;
    				break;
    			case 'stock':
    				$val = 3;
    				break;
    			default:
    				$val = 'none';
    		}
    		
    		if ($val == 'none')
    			echo '0';
    		else
    		{
    			$fields = crm_all_field::where('type_id', $val)->get();

	    		$html = '<input type="hidden" name="field" value="'.$_POST['tab_id'].'">
	    			<table class="table table-sm">
	    			<tr>
	    				<td><input type="checkbox" id="checkAllFields" name="checkAllFields"></td>
	    				<td colspan="2" class="text-primary">Отметить все поля</td>
	    			</tr>';
	    		foreach ($fields as $field) 
	    		{
	    			$html .= '<tr>
	    				<td><input type="checkbox" name="fieldsCheckbox[]" value="'.$field->id.'" field_id="'.$field->id.'" type_id="'.$field->type_id.'"></td>
	    				<td>' . $field->name . '</td>
	    			</tr>';
	    		}
	    		$html .= '</table>';

	    		echo json_encode($html);
    		}
    		
    	}
    	else
    		echo '0';
    }


    public function getCurrentSettings(Request $request)
    {
    	if ($request->has('tab_id'))
    	{
    		$settings = crm_setting::where('field', $request->tab_id)->get();

    		if (count($settings) == 0)
    		{
    			echo '0';
    		}
    		else
    		{
    			$html = '<table class="table table-sm">';
	    		foreach ($settings as $setting) 
	    		{
	    			$html .= '<tr>
	    				<td>'.$setting->name.'</td>';
	    				if ($setting->active == 1)
	    					$html .= '<td><span class="text-success">Сейчас активна</span></td>';
	    				else
	    					$html .= '<td><a href="/crm/setactive/'.$setting->id.'" class="text-dark">Сделать активной</a></td>';
	    			$html .= '</tr>';
	    		}
	    		$html .= '</table>';
		    	
		    	echo json_encode($html);
    		}	
    	}
    	else
    		echo '0';
    }
}
