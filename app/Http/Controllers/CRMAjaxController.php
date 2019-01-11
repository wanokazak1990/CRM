<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_all_field;
use App\crm_setting;

use App\_tab_traffic;
use App\_tab_client;
use App\_tab_stock;

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

    public function crmgetcontent(Request $request)
    {
        if($request->has('model'))
        {
            $class_name = 'App\\'.$request->model;//создаём имя класса вкладки с которой сделан переход         
            $query = new $class_name();//получаем объект вкладки с которой сделан клик 
            switch ($request->model) {
                case '_tab_client':
                    $query->with('model')->with('manager')->with('action');
                    break;

                case '_tab_traffic':
                    $query->with('model')->with('manager')->with('action')->with('admin');
                    break;

                case '_tab_stock':
                	$query->with('car');
                    break;
                
                default:
                    # code...
                    break;
            }
            $list = $query->paginate(2);

            foreach ($list as $key => $item) {
                $help = clone $item;
                if(isset($item->desired_model))          $item->desired_model        =   @$help->model->name;
                if(isset($item->manager_id))             $item->manager_id           =   @$help->manager->name;
                if(isset($item->assigned_action_id))     $item->assigned_action_id   =   @$help->action->name;
                if(isset($item->action_date))            $item->action_date          =   @date('d.m.Y',$item->action_date);
                if(isset($item->creation_date))          $item->creation_date        =   @date('d.m.Y',$item->creation_date);
                if(isset($item->traffic_type_id))        $item->traffic_type_id      =   @$help->trafic_type->name;
                if(isset($item->admin_id))               $item->admin_id             =   @$help->admin->name;
                if(isset($item->action_time))            $item->action_time          =   @date('H:i',$item->action_time);
                if(isset($item->car_id))
                {
                	unset($item->car_id);
                	$item->model = @$help->car->model->name;
                	$item->complect = @$help->car->complect->name;
                }
            }
            
            $titles = crm_all_field::where('type_id',$class_name::$tab_index)->get();

            $links = (string)$list->appends(['model'=>$request->model])->links();

            echo json_encode([
                            'list'=>$list,
                            'links'=>$links,
                            'titles'=>$titles
            ]);
        }
    }
}
