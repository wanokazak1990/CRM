<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_all_field;

class CRMAjaxController extends Controller
{
    public function getCurrentFields()
    {
    	if (isset($_POST['tab_id']))
    	{
    		switch ($_POST['tab_id']) {
    			case 'traffic':
    				$val = 1;
    				break;
    			case 'contacts':
    				$val = 2;
    				break;
    			case 'refusals':
    				$val = 2;
    				break;
    			case 'reserves':
    				$val = 3;
    				break;
    			case 'deals':
    				$val = 3;
    				break;
    			case 'demo':
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

	    		$html = '<table class="table table-sm">
	    			<tr>
	    				<td><input type="checkbox" id="checkAllFields" name="checkAllFields"></td>
	    				<td colspan="3">Отметить все поля</td>
	    			</tr>';
	    		foreach ($fields as $field) 
	    		{
	    			$html .= '<tr id="'.$field->id.'">
	    				<td><input type="checkbox" name="fieldsCheckbox"></td>
	    				<td>' . $field->name . '</td>
	    				<td><input type="text" class="form-control" placeholder="Ширина, px"></td>
	    			</tr>';
	    		}
	    		$html .= '</table>';

	    		echo json_encode($html);
    		}
    		
    	}
    	else
    		echo '0';
    }
}
