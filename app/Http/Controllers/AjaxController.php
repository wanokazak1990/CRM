<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\oa_color;
use App\oa_option;
use App\oa_model;
use App\oa_motor;
use App\type_motor;
use App\type_transmission;
use App\type_wheel;
use App\pack;
use App\oa_complect;
use App\option_parent;
use App\oa_brand;
use App\complect_option;

class AjaxController extends Controller
{
    
    //ВЕРНЁТ КОЛЕКЦИЮ ЦВЕТОВ ОБЪЕКТОВ oa_color если существует request->[brand_id,model_id,complect_id]
    //ЛИБО ВЕРНЁТ ОБЪЕКТ oa_color ЕСЛИ СУЩЕСТВУЕТ request->color_id
    public function getcolor(Request $request)
    {
    	$colorlist = array();
    	$color = new oa_color();
    	if(isset($_POST['brand_id']))
    	{
    		$colorlist = $color->where('brand_id',$request->brand_id)->get();
    	}
    	if(isset($_POST['model_id']))
    	{
    		$colorlist = $color->select('oa_colors.*')
    						->join('model_colors','model_colors.color_id','=','oa_colors.id')
    						->where('model_colors.model_id',$request->model_id)
    						->get();
    	}
    	if(isset($_POST['complect_id']))
    	{
    		$colorlist = $color->select('oa_colors.*')
    			->join('complect_colors','complect_colors.color_id','=','oa_colors.id')
    			->where('complect_colors.complect_id',$request->complect_id)
    			->get();
    	}
    	if($request->has('color_id'))
    	{
    		$colorlist = $color->select('oa_colors.*')->where('id',$request->color_id)->first();
    	}
    	echo json_encode($colorlist);
    }


    //ВЕРНЁТ ГОТОВУЮ HTML ПЛИТКУ ДЛЯ ОБОРУДОВАНИЯ oa_option
    public function getOption(Request $request,$str='')
    {
    	$option = new oa_option();
    	$optionlist = $option
			    		->select('oa_options.id','oa_options.name','oa_options.parent_id')
			    		->join('option_brands','option_brands.option_id','=','oa_options.id')
			    		->where('brand_id',$request->brand_id)
			    		->orderBy('oa_options.parent_id')
			    		->orderBy('oa_options.name')
			    		->get();
		foreach ($optionlist as $key => $option) 
		{
			if($key==0)
			{
				$str .= "<div>";
					$str .= '<h4>'.option_parent::find($option->parent_id)->name.'</h4>';
				$str .= "</div>";
				$str .= '<div class="column">';
			}
			elseif($optionlist[$key-1]->parent_id != $option->parent_id)
			{
				$str .= "</div>";
				$str .= '<div class="">';
					$str .= '<h4>'.option_parent::find($option->parent_id)->name.'</h4>';
				$str .= '</div>';
				$str .= '<div class="column">';
			}
			$str .= '<label>';
				$str .= '<input 
					type="checkbox" 
					name="pack_option[]" 
					value="'.$option->id.'"
				>';
				$str .= mb_strimwidth($option->name, 0, 40, "...");
				if(mb_strlen($option->name)>40)
				{
					$str .= '<span 
						style="float: right; margin-top: -13px;" 
						class="glyphicon glyphicon-info-sign" 
						data-toggle="tooltip" 
						data-placement="left"
						title="'.$option->name.'"
					>
					</span>';
				}
			$str .= '</label>';
		}
    	echo ($str);
    }

    //ВЕРНЁТ КОЛЕКЦИЮ МОДЕЛЕЙ
    //ЕСЛИ УКАЗАН request->brand_id то вернёт колекцию в зависимости от бренда
    public function getmodels(Request $request)
    {
    	$query = oa_model::select('*');
    	if($request->has('brand_id'))
    		$query->where('brand_id',$request->brand_id);
    	$modellist = $query->orderBy('sort')->get();
    	echo json_encode($modellist);
    }

    //Вернёт массив [id => motorName]-nameForSelect моторов
    //ЕСЛИ УКАЗАН request->brand_id то вернёт колекцию в зависимости от бренда
    public function getmotors(Request $request)
    {
    	$result = array();

    	$query = oa_motor::select("*");
    	
    	if($request->has('brand_id'))
    		$query->where('brand_id',$request->brand_id);
    	
    	$motorlist = $query->orderBy('brand_id')->orderBy('type_id')->orderBy('size')->orderBy('power')->get();
    	
    	foreach ($motorlist as $key => $item) {
    		$result[] = $item->nameForSelect();
    	}
    	
    	echo json_encode($result);
    }

    public function getmotor(Request $request)
    {
        $result = array();

        $result = oa_motor::with('type')->with('wheel')->with('transmission')->find($request->id);
        
        $result->type_id = @$result->type->name;
        $result->wheel_id = @$result->wheel->name;
        $result->transmission_id = @$result->transmission->name;
        echo json_encode($result);
    }


    //ВЕРНЁТ КОЛЛЕКЦИЮ ПАКЕТОВ ОПЦИЙ
    //В ЗАВИСИМОСТИ ОТ ТОГО КАКОЙ ПАРАМЕТР ПЕРЕДАН
    //КОЛЛЕКЦИЯ БУДЕТ ЗАПОЛНЕНА ОПРЕДЕЛЁННЫМИ ПАКЕТАМИ
    //model_id - ДАСТ ВСЕ ПАКЕТЫ ДЛЯ ДАННОЙ МОДЕЛИ
    //complect_id - ДАСТ ВСЕ ПАКЕТЫ ДЛЯ ДАННОЙ КОМПЛЕКТАЦИИ
    //В ЦИКЛЕ ПРОСТО В ЭЛЕМЕНТЕ КОЛЛЕКЦИИ СОЗДАЁМ ДИНАМИЧЕСКОЕ СВОЙСТВО optionlist
    //В КОТОРОЕ ЗАПИШЕМ ТО ОБОРУДОВАНИЕ КОТОРОЕ ВКЛЮЧЕНО В ЭТОТ ПАКЕТ
    public function getpacks(Request $request)
    {
    	$packlist = array();
    	if(isset($_POST['model_id']))
    	{
	    	$packlist = pack::select('packs.*')
	    				->join('model_packs','model_packs.pack_id','=','packs.id')
	    				->where('model_packs.model_id',$request->model_id)
	    				->get();
    	}
    	if(isset($_POST['complect_id']))
    	{
	    	$packlist = pack::select('packs.*')
	    				->join('complect_packs','complect_packs.pack_id','=','packs.id')
	    				->where('complect_packs.complect_id',$request->complect_id)
	    				->get();
    	}
    	foreach ($packlist as $key => $pack) 
    	{
    		$options = $pack->option;
    		$pack->optionlist = '';
    		foreach ($options as $key => $option) 
    		{
    			$pack->optionlist .= $option->option->name;
    		}
    	}
    	echo json_encode($packlist);
    }


    //ВЕРНЁТ МАССИВ КОМПЛЕКТАЦИИ МОДЕЛИ (В ВИДЕ [complect_id]=>fullname)
    public function getcomplects(Request $request)
    {	
    	$complects = oa_complect::where('model_id',$request->input('model_id'))->get();
    	foreach ($complects as $key => $complect) {
    		if($complect->motor)
    			$complects[$key]->fullname = $complect->name.' '.$complect->motor->nameForSelect()['name'] ;
    	}
    	echo json_encode($complects);
    }


    //ИЗМЕНЕНИЕ СОРТИРОВКИ
    // data_type передаёт название ларавель модели - например oa_complect
    // data_id передаёт id объета - например 30, что значит комплектация под id 30
    // sort передаёт новый порядок сортировки
    public function changesort(Request $request)
    {
    	if($request->has('data_type'))
    		if($request->has('data_id'))
    		{
	    		$obj = $request->data_type::find($request->data_id); 
	    		$obj->sort = $request->sort;
	    		echo $obj->update(); 	
	    		return;
    		}
    	echo "0";
    	return;
    }

    //вернёт комплектацию по id (название complectPrice - не несёт смысла, это не цена комплектации (позже исправлю))
    public function complectprice(Request $request)
    {
    	if($request->has('id'))
    	{
    		$res = oa_complect::where('id',$request->id)->first();
    		echo $res;
    		return;
    	}
    	echo 0;
    	return;
    }

    //вернёт стоимость пакетов id это строка разделитель которой запятая
    public function packprice(Request $request)
    {
    	if($request->has('id'))
    	{
    		$mas = explode(',', $request->id);
    		$res = pack::whereIn('id',$mas)->sum('price');
    		echo $res;
    		return;
    	}
    	echo 0;
    	return;
    }

    public function getbrand(Request $request)
    {
    	if($request->has('id'))
    	{
    		$res = oa_brand::where('id',$request->id)->first();
    		echo $res;
    		return;
    	}
    	echo 0;
    	return;
    }


    public function getmodel(Request $request)
    {
    	if($request->has('id'))
    	{
    		$res = oa_model::where('id',$request->id)->first();
    		echo $res;
    		return;
    	}
    	echo 0;
    	return;
    }


    public function getcomplectoption(Request $request)
    {
        if($request->has('complect_id'))
        {
            $options = oa_option::select('oa_options.*')
                    ->join('complect_options','complect_options.option_id','=','oa_options.id')
                    ->where('complect_options.complect_id',$request->complect_id)
                    ->orderBy('oa_options.parent_id')
                    ->orderBy('oa_options.name')
                    ->get();
            return $options->toJson();
        }
    }
}
