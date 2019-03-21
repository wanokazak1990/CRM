<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_ofu_product extends Model
{
    public $timestamps = false;

    public static function getProductBlock($worklist = '')
    {
    	if ($worklist != '')
    	{
    		$ofu_products = crm_ofu_product::where('worklist_id', $worklist)->get();

    		if (count($ofu_products) > 0)
	    	{
	    		$blocks = '';
	    		foreach ($ofu_products as $key => $product) 
	    		{
	    			$blocks .= self::createHtmlBlock($worklist, $product);
	    		}

	    		return $blocks;
	    	}
	    	else
    			return self::createHtmlBlock($worklist);
    	}
    	else
    		return self::createHtmlBlock($worklist);
    }

    public static function createHtmlBlock($worklist, $ofu_product = [])
    {
    	$users = user::pluck('name', 'id');
    	$partners = crm_partner::where('brand_id', 1)->pluck('name', 'id');
    	
    	$car_id = crm_car_selection::where('worklist_id', $worklist)->first()->car_id;
        $car = avacar::find($car_id);

    	$services = company::where('razdel', 3)->get();
    	if (count($services) > 0)
    	{
    		$products = [];

    		foreach ($services as $key => $service) 
	    	{
	    		if ($service->checkCompany($car) == true)
	    			$products[] = $service;
	    	}
    	}
    	
		
		$block = '
    		<div class="my-2 ofu-block">
				<div class="input-group">
					<span class="col-3">Консультант</span>
					<span class="col-3">Продукт</span>
					<span class="col-3">Партнер</span>
					<span class="col-3">Стоимость продукта</span>
				</div>

				<div class="input-group mb-1">
					<select class="col-3 form-control ofu-block-authors" name="wl_ofu[blocks][][author]">';
					if (empty($ofu_product))
						$block .= '<option selected disabled>Укажите параметр</option>';
					else
						$block .= '<option disabled>Укажите параметр</option>';

					foreach ($users as $id => $name) 
					{
						if (empty($ofu_product))
							$block .= '<option value="'.$id.'">'.$name.'</option>';
						else
						{
							if ($ofu_product->author_id == $id)
								$block .= '<option value="'.$id.'" selected>'.$name.'</option>';
							else
								$block .= '<option value="'.$id.'">'.$name.'</option>';
						}
					}

		$block .= '</select>

					<select class="col-3 form-control ofu-block-products"  name="wl_ofu[blocks][][product]">';
					if (empty($ofu_product))
						$block .= '<option selected disabled>Укажите параметр</option>';
					else
						$block .= '<option disabled>Укажите параметр</option>';

					foreach ($products as $key => $product) 
					{
						if (empty($ofu_product))
							$block .= '<option value="'.$product->id.'">'.$product->name.'</option>';
						else
						{
							if ($ofu_product->product_id == $product->id)
								$block .= '<option value="'.$product->id.'" selected>'.$product->name.'</option>';
							else
								$block .= '<option value="'.$product->id.'">'.$product->name.'</option>';
						}
					}

		$block .= '</select>

					<select class="col-3 form-control ofu-block-partners" name="wl_ofu[blocks][][partner]">';
					if (empty($ofu_product))
						$block .= '<option selected disabled>Укажите параметр</option>';
					else
						$block .= '<option disabled>Укажите параметр</option>';

					foreach ($partners as $id => $partner) 
					{
						if (empty($ofu_product))
							$block .= '<option value="'.$id.'">'.$partner.'</option>';
						else
						{
							if ($ofu_product->partner_id == $id)
								$block .= '<option value="'.$id.'" selected>'.$partner.'</option>';
							else
								$block .= '<option value="'.$id.'">'.$partner.'</option>';
						}
					}

		$block .= '</select>';
				
				if (empty($ofu_product))
					$block .= '<input type="text" class="col-3 form-control ofu-block-price" name="wl_ofu[blocks][][price]" placeholder="Стоимость, р.">';
				else
					$block .= '<input type="text" class="col-3 form-control ofu-block-price" name="wl_ofu[blocks][][price]" placeholder="Стоимость, р." value="'.$ofu_product->price.'">';
		
		$block .= '</div>
				
				<div class="input-group">
					<span class="col-3">Дата оформления</span>
					<span class="col-3">Дата окончания</span>
					<span class="col-3">КВ за продукт</span>
					<span class="col-3">Дата выплаты КВ</span>
				</div>

				<div class="input-group">';
				if (empty($ofu_product))
				{
					$block .= '<input type="text" name="wl_ofu[blocks][][creation_date]" class="col-3 form-control ofu-block-creation-date" placeholder="Дата">
					<input type="text" class="col-1 form-control ofu-block-months" placeholder="Мес.">
					<input type="text" name="wl_ofu[blocks][][end_date]" class="col-2 form-control ofu-block-end-date" placeholder="Дата">
					<input type="text" name="wl_ofu[blocks][][profit]" class="col-3 form-control ofu-block-profit" placeholder="Сумма, р.">
					<input type="text" name="wl_ofu[blocks][][profit_date]" class="col-3 form-control ofu-block-profit-date" placeholder="Дата">';
				}
				else
				{
					$block .= '<input type="text" name="wl_ofu[blocks][][creation_date]" class="col-3 form-control ofu-block-creation-date" placeholder="Дата" value="'.date('d.m.Y', $ofu_product->creation_date).'">

					<input type="text" class="col-1 form-control ofu-block-months" placeholder="Мес.">

					<input type="text" name="wl_ofu[blocks][][end_date]" class="col-2 form-control ofu-block-end-date" placeholder="Дата" value="'.date('d.m.Y', $ofu_product->end_date).'">

					<input type="text" name="wl_ofu[blocks][][profit]" class="col-3 form-control ofu-block-profit" placeholder="Сумма, р." value="'.$ofu_product->profit.'">

					<input type="text" name="wl_ofu[blocks][][profit_date]" class="col-3 form-control ofu-block-profit-date" placeholder="Дата" value="'.date('d.m.Y', $ofu_product->profit_date).'">';
				}
		$block .= '</div>

				<div class="input-group">
					<div class="col-12 d-flex justify-content-end">
						<a href="javascript://" class="ofu-remove-block">Удалить рассчет</a>
					</div>
				</div>
			</div>
    	';

    	return $block;
    }
}
