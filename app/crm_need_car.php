<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\crm_worklist;

class crm_need_car extends Model
{
    public $timestamps = false;

    public function getPayTypeName()
    {
        switch ($this->pay_type) {
            case '1':
                return 'Неизвестно';
                break;
            case '2':
                return 'Наличными';
                break;
            case '3':
                return 'Кредит';
                break;
            case '4':
                return 'Лизинг';
                break;
            
            default:
                return '';
                break;
        }
    }
    /**
     * Получить блоки сохраненных машин клиента в Подборе по потребностям
	 * Если таких нет - получить начальный блок выбора модели
     */
    public static function getCarBlocks($worklist_id)
    {
    	$cars = crm_need_car::where('worklist_id', $worklist_id)->get();

    	if (count($cars) > 0)
    	{
    		$blocks = '';
	    	foreach ($cars as $key => $car)
	    	{
	    		$blocks .= crm_need_car::getBlock($car->model_id, $car->transmission_id, $car->wheel_id);
	    	}
    	}
    	else
    	{
    		$car_id = crm_worklist::find($worklist_id)->traffic->desired_model;
    		$blocks = crm_need_car::getBlock($car_id);
    	}
    	
    	return $blocks;
    }

    /**
     * Получить список опций автомобиля клиента в Подборе по потребностям
     */
    public static function getCarOptions($worklist_id)
    {
    	$car = crm_need_car::where('worklist_id', $worklist_id)->first();

    	if ($car != null)
    	{
    		if ($car->options != null)
    			return json_decode($car->options);
    		else
    			return null;
    	}
    	else
    		return null;
    }

    /**
     * Получить тип (форму) покупки автомобиля клиента в Подборе по потребностям
     */
    public static function getPurchaseType($worklist_id)
    {
    	$needcar = crm_need_car::where('worklist_id', $worklist_id)->first();

    	if ($needcar != null)
    	{
    		if ($needcar->purchase_type != null)
    			return $needcar->purchase_type;
    		else
    			return null;
    	}
    	else
    		return null;
    }

    /**
     * Получить тип (способ) оплаты автомобиля клиента в Подборе по потребностям
     */
    public static function getPayType($worklist_id)
    {
    	$needcar = crm_need_car::where('worklist_id', $worklist_id)->first();

    	if ($needcar != null)
    	{
    		if ($needcar->pay_type != null)
    			return $needcar->pay_type;
    		else
    			return null;
    	}
    	else
    		return null;
    }

    /**
     * Получить первый взнос за автомобиль клиента в Подборе по потребностям
     */
    public static function getFirstPay($worklist_id)
    {
    	$needcar = crm_need_car::where('worklist_id', $worklist_id)->first();

    	if ($needcar != null)
    	{
    		if ($needcar->firstpay != null)
    			return $needcar->firstpay;
    		else
    			return null;
    	}
    	else
    		return null;
    }


    /**
     * Отрисовка блока машины в Подборе по потребностям
     * $model_id - Модель
     * $transmission_id - КПП
     * $wheel_id - Привод
	 */
    public static function getBlock($model_id, $transmission_id = null, $wheel_id = null)
    {
    	$models = \App\oa_model::pluck('name','id');
    	$transmissions = \App\type_transmission::pluck('name','id');
    	$wheels = \App\type_wheel::pluck('name','id');

    	$block = '<div class="col-3 border">
			<div class="d-flex">
				<label class="flex-grow-1">Выберите модель</label>
				<a href="javascript://" class="removeSelectedCar text-danger"><i class="icofont-close"></i></a>
			</div>
			
			
			<select class="wl_need_model form-control" name="wl_need_model">';
			if ($model_id == null)
				$block .= '<option selected disabled>Укажите параметр</option>';
			else
				$block .= '<option disabled>Укажите параметр</option>';

			foreach ($models as $key => $value)
			{
				if ($key == $model_id)
					$block .= '<option value='.$key.' selected>'.$value.'</option>';
				else
					$block .= '<option value='.$key.'>'.$value.'</option>';
			}

		$block .= '</select>

			<select class="wl_need_transmission form-control" name="wl_need_transmission">';
			if ($transmission_id == null)
				$block .= '<option selected disabled>Укажите параметр</option>';
			else
			{
				$block .= '<option disabled>Укажите параметр</option>';
			}

			foreach ($transmissions as $key => $value)
			{
				if ($key == $transmission_id)
					$block .= '<option value='.$key.' selected>'.$value.'</option>';
				else
					$block .= '<option value='.$key.'>'.$value.'</option>';
			}
		

		$block .= '</select>

			<select class="wl_need_wheel form-control" name="wl_need_wheel">';
			if ($wheel_id == null)
				$block .= '<option selected disabled>Укажите параметр</option>';
			else
				$block .= '<option disabled>Укажите параметр</option>';

			foreach ($wheels as $key => $value)
			{
				if ($key == $wheel_id)
					$block .= '<option value='.$key.' selected>'.$value.'</option>';
				else
					$block .= '<option value='.$key.'>'.$value.'</option>';
			}

		$block .= '</select>
				
		</div>';

		return $block;
    }
}
