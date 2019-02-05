<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\crm_all_field;
use App\crm_setting;

use App\_tab_traffic;
use App\_tab_client;
use App\_tab_stock;
use App\crm_traffic;
use App\crm_client;
use App\avacar;

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
    
    //ФУНКЦИЯ ВЕРНЁТ КОЛЛЕКЦИЮ ДАННЫХ ВЫБИРАЕМЫХ ИЗ ТАБЛИЦЫ УКАЗАННОЙ в $request->has('model'), $request->has('model') - хранит название модели текущей вкладки CRM

    //ПОМИМО ЭТОГО ВЕРНЁТ КОЛЛЕКЦИЮ ЗАГОЛОВКОВ в ЗАВИСИМОСТИ от выбранной модели (название модели хранится в $request->has('model'), так как модель вкладки ($request->has('model')) известна, то создавая объект данной вкладки, будет известно статичное свойств tab_index, которое хранит type_id тех заголовков, которые должны отображаться в данной вкладке

    //Так же вернёт готовую html строку с пагинацией

    public function crmgetcontent(Request $request,$list = array(),$mas=array())
    {
        if($request->has('model'))
        {
            $class_name = 'App\\'.$request->model;//создаём имя класса вкладки с которой сделан переход         
            $class_name = new $class_name();//получаем объект вкладки с которой сделан клик 
            switch ($request->model) {
                case '_tab_client':
                    $list = crm_client::with('contact')->with('traffic')->orderBy('id','desc')->paginate(20);
                    foreach ($list as $key => $item) {
                            $mas[$key]['id']                = $item->id;
                            $mas[$key]['name']              = $item->getFullName();
                            $mas[$key]['phone']             = @$item->contact->phone;
                            $mas[$key]['email']             = @$item->contact->email;;
                            $mas[$key]['desired_model']     = @$item->traffic->model->name;
                            $mas[$key]['manager']           = @$item->traffic->manager->name;
                            $mas[$key]['assigned_action']   = @$item->traffic->assigned_action->name;
                            $mas[$key]['action_date']    = @($item->traffic->action_date)?date('d.m.Y',$item->traffic->action_date):'';
                    }
                    $titles = crm_all_field::where('type_id',$class_name::$tab_index)->get();
                    $links = (string)$list->appends(['model'=>$request->model])->links();
                    echo json_encode([
                        'list'=>$mas,
                        'links'=>$links,
                        'titles'=>$titles
                    ]);
                    break;

                case '_tab_traffic':
                    $list = crm_traffic::with('model')
                        ->with('manager')
                        ->with('assigned_action')
                        ->with('admin')
                        ->with('manager')
                        ->with('traffic_type')
                        ->orderBy('id','desc')->paginate(20);
                    foreach ($list as $key => $item) {
                            $mas[$key]['id']                = $item->id;
                            $mas[$key]['creation_date']     = @($item->creation_date)?date('d.m.Y',$item->creation_date):'';
                            $mas[$key]['type']              = @$item->traffic_type->name;
                            $mas[$key]['desired_model']     = @$item->model->name;
                            $mas[$key]['client']            = @$item->client->getFullName();
                            $mas[$key]['comment']           = $item->comment;
                            $mas[$key]['manager']           = @$item->manager->name;
                            $mas[$key]['admin']             = @$item->admin->name;
                            $mas[$key]['assigned_action']   = @$item->assigned_action->name;
                            $mas[$key]['action_date']       = @($item->action_date)?date('d.m.Y',$item->action_date):'';
                            $mas[$key]['action_time']       = @($item->action_time)?date('H:i',$item->action_time):'';
                    }
                    $titles = crm_all_field::where('type_id',$class_name::$tab_index)->get();
                    $links = (string)$list->appends(['model'=>$request->model])->links();
                    echo json_encode([
                        'list'=>$mas,
                        'links'=>$links,
                        'titles'=>$titles
                    ]);
                    break;

                case '_tab_stock':
                    $list = avacar::with('getAuthor')
                        ->with('getDateOrder')
                        ->with('getDatePlanned')
                        ->with('getDateBuild')
                        ->with('getDateReady')
                        ->with('brand')
                        ->with('model')
                        ->with('complect')
                        ->with('color')
                        ->get();
                    foreach ($list as $key => $item) {
                            $mas[$key]['id'] = $item->id;
                            $mas[$key]['stage_deal'] = 'Этап сделки';
                            $mas[$key]['status_pts'] = 'status_pts';
                            $mas[$key]['stage_delivery'] = 'etap postavki';
                            $mas[$key]['logist_marker'] = 'logist_marker';
                            $mas[$key]['author'] = @$item->getAuthor->name;
                            $mas[$key]['date_order'] = date('d.m.Y',@$item->getDateOrder->date);
                            $mas[$key]['date_planned'] = date('d.m.Y',@$item->getDatePlanned->date);
                            $mas[$key]['date_tochnee'] = 'tochnee';
                            $mas[$key]['date_build'] = date('d.m.Y',@$item->getDateBuild->date);
                            $mas[$key]['date_ready'] = date('d.m.Y',@$item->getDateReady->date);
                            $mas[$key]['loc_ship'] = 'локация отгрузки';
                            $mas[$key]['date_ship'] = date('d.m.Y',@$item->getDateShip->date);
                            $mas[$key]['date_storage'] = date('d.m.Y',@$item->date_storage);
                            $mas[$key]['technic'] = @$item->getTechnic->name;
                            $mas[$key]['type_ship'] = 'type_ship';
                            $mas[$key]['raschet_zakup'] = 'raschet_zakup';
                            $mas[$key]['fakt_zakup'] = 'fakt_zakup';
                            $mas[$key]['sum_discount'] = 'sum_discount';
                            $mas[$key]['discount_ship'] = 'discount_ship';
                            $mas[$key]['discount_vozmeshenee'] = 'discount_vozmeshenee';
                            $mas[$key]['date_vozmechenee'] = 'date_vozmechenee';
                            $mas[$key]['cmment'] = 'comment';
                            $mas[$key]['date_noklad'] = 'date_noklad';
                            $mas[$key]['begin_otsrochka'] = 'begin_otsrochka';
                            $mas[$key]['period_otsrochka'] = 'period_otsrochka';
                            $mas[$key]['end_otsrochka'] = 'end_otsrochka';
                            $mas[$key]['date_sale_pts'] = 'date_sale_pts';
                            $mas[$key]['date_prichod_pts'] = 'date_pts';
                            $mas[$key]['monitor'] = 'monitor';

                            $mas[$key]['year'] = @$item->year;
                            $mas[$key]['brand'] = @$item->brand->name;
                            $mas[$key]['model'] = @$item->model->name;
                            $mas[$key]['complect'] = @$item->complect->name.' '.@$item->complect->motor->forAdmin();
                            $mas[$key]['pack'] = 'packs';
                            $mas[$key]['color_code'] = $item->color->rn_code;
                            $mas[$key]['color_name'] = $item->color->name;
                            $mas[$key]['vin'] = $item->vin;
                            $mas[$key]['order_number'] = $item->order_number;
                            $mas[$key]['price_stock'] = 'Цена по прайсу';
                            $mas[$key]['price_pack'] = $item->packPrice();
                            $mas[$key]['price_dops'] = $item->dopprice;
                            $mas[$key]['price_discount'] = 'СКИДКА';
                            $mas[$key]['price_sell'] = 'Цена продажи';
                    }
                    $titles = crm_all_field::where('type_id',$class_name::$tab_index)->get();
                    $links = '';
                    echo json_encode([
                        'list'=>$mas,
                        'links'=>$links,
                        'titles'=>$titles
                    ]);
                    break;
                
                default:
                    # code...
                    break;
            }           
        }
    }

    public function getJournal(Request $request)
    {
        $traffics = crm_traffic::
            with('traffic_type')->
            with('client')->
            with('manager')->
            with('assigned_action')->
            with('model')->
            orderBy('id','DESC')->
            get()->
            toJson();
        return $traffics;
    }
}
