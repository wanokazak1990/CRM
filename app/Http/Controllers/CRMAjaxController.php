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
    			$html = '';
	    		foreach ($settings as $setting) 
	    		{
                    $html .= '<div class="input-group no-gutters border-bottom p-1">';

	    			$html .= '<div class="col-5">'.$setting->name.'</div>';
    				
                    if ($setting->active == 1)
    					$html .= '<div class="col-3 text-success">Сейчас активна</div>';
    				else
    					$html .= '<div class="col-3">
                            <a href="javascript://" data-id="'.$setting->id.'" class="setting-set-active">Сделать активной</a>
                        </div>';

                    $html .= '<div class="col-2">
                            <a href="javascript://" data-id="'.$setting->id.'" class="text-info setting-edit">Изменить</a>
                        </div>';

                    $html .= '<div class="col-2">
                            <a href="javascript://" data-id="'.$setting->id.'" class="text-danger setting-delete">Удалить</a>
                        </div>';
                    
                    $html .= '</div>';
	    		}
		    	
		    	echo json_encode($html);
    		}	
    	}
    	else
    		echo '0';
    }


    public function getTitle($type_id)
    {
        return crm_all_field::where('type_id',$type_id)->pluck('name','id')->toArray();;
    }
    
    //ФУНКЦИЯ ВЕРНЁТ КОЛЛЕКЦИЮ ДАННЫХ ВЫБИРАЕМЫХ ИЗ ТАБЛИЦЫ УКАЗАННОЙ в $request->has('model'), $request->has('model') - хранит название модели текущей вкладки CRM

    //ПОМИМО ЭТОГО ВЕРНЁТ КОЛЛЕКЦИЮ ЗАГОЛОВКОВ в ЗАВИСИМОСТИ от выбранной модели (название модели хранится в $request->has('model'), так как модель вкладки ($request->has('model')) известна, то создавая объект данной вкладки, будет известно статичное свойств tab_index, которое хранит type_id тех заголовков, которые должны отображаться в данной вкладке

    //Так же вернёт готовую html строку с пагинацией

    public function crmgetcontent(Request $request, $list = array(), $mas = array())
    {
        if ($request->has('model'))
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
                    
                    $titles = $this->getTitle($class_name::$tab_index);

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
                    
                    $titles = $this->getTitle($class_name::$tab_index);

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
                        ->with('getProvision')
                        ->with('getDateProvisionLast')
                        ->orderBy('id','DESC')
                        ->get();
                    
                    $tr = new \App\autostock_helper($list);
                    $tr->makeTableData();

                    $titles = $this->getTitle($class_name::$tab_index);

                    $links = '';

                    echo json_encode([
                        'list'=>$tr->response,
                        'links'=>$links,
                        'titles'=>$titles
                    ]);
                    break;
                
                default:
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


    /**
     * Найти в автоскладе (РЛ -> Параметры -> Подбор по потребностям)
     *
     * $options - массив с потребностями (опциями)
     * $cars - массив параметров машин (может содержать модель, тип кпп и тип привода для каждой машины)
     */
    public function getCarsByNeeds(Request $request, $options=array())
    {
        if ($request->wl_need_option)
            $options = explode(',', $request->wl_need_option);
        $cars = $request->data;
        $cars = json_decode($cars);

        $query = avacar::with('getAuthor')
            ->with('getDateOrder')
            ->with('getDatePlanned')
            ->with('getDateBuild')
            ->with('getDateReady')
            ->with('brand')
            ->with('model')
            ->with('complect')
            ->with('color')
            ->leftJoin('oa_models as model', 'model.id', '=', 'avacars.model_id')
            ->leftJoin('oa_complects as complect', 'complect.id', '=', 'avacars.complect_id')
            ->leftJoin('oa_motors as motor', 'complect.motor_id', '=', 'motor.id')
            ->join('_view_caroption as installed', 'installed.id', '=', 'avacars.id')
            ->join('_view_car_price as carprice', 'carprice.id', '=', 'avacars.id');
            
        if (!empty($options))
            $query->whereIn('installed.filter_order', $options);

        if (is_array($cars))
            $query->where(function ($query) use ($cars) {
                foreach ($cars as $item) 
                    $query->orWhere(function($query) use ($item){
                        if($item->model)            $query->where('model.id',$item->model);
                        if($item->transmission)     $query->where('motor.transmission_id',$item->transmission);
                        if($item->wheel)            $query->where('motor.wheel_id',$item->wheel);
                    });
            });
            
        /*if($request->wl_need_sum)
            $query->where('carprice.totalprice', '<=', $request->wl_need_sum);*/

        $query->groupBy('avacars.id');
        if (count($options) > 0)
            $query->havingRaw("COUNT(avacars.id) = ".count($options));
        $list = $query->orderBy('avacars.id','DESC')->get();

        $result = new \App\autostock_helper($list);
        $result->makeTableData();

        $titles = $this->getTitle(3);

        $links = '';
        echo json_encode([
            'list'=>$result->response,
            'links'=>$links,
            'titles'=>$titles
        ]);
    }
}
