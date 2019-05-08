<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_ofu_product extends Model
{
    public $timestamps = false;

    public static function getProductBlock($worklist)
    {
    	//if ($worklist != '')
    	//{
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
    	//}
    	//else
    	//	return self::createHtmlBlock($worklist);
    }

    public static function sumProfit($worklistId)
    {
    	return self::where('worklist_id',$worklistId)->sum('profit');
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
				<div class="input-group no-gutters">
					<div class="col-3">Консультант</div>
					<div class="col-3">Продукт</div>
					<div class="col-3">Партнер</div>
					<div class="col-3">Стоимость продукта</div>
				</div>

				<div class="input-group no-gutters mb-1">
					<div class="col-3"><select class="form-control ofu-block-authors" name="wl_ofu[blocks][][author]">';
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

		$block .= '</select></div>

					<div class="col-3"><select class="form-control ofu-block-products"  name="wl_ofu[blocks][][product]">';
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

		$block .= '</select></div>

					<div class="col-3"><select class="form-control ofu-block-partners" name="wl_ofu[blocks][][partner]">';
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

		$block .= '</select></div>';
				
				if (empty($ofu_product))
					$block .= '<div class="col-3"><input type="text" class="form-control ofu-block-price" name="wl_ofu[blocks][][price]" placeholder="Стоимость, р."></div>';
				else
					$block .= '<div class="col-3"><input type="text" class="form-control ofu-block-price" name="wl_ofu[blocks][][price]" placeholder="Стоимость, р." value="'.$ofu_product->price.'"></div>';
		
		$block .= '</div>
				
				<div class="input-group no-gutters">
					<div class="col-3">Дата оформления</div>
					<div class="col-3">Дата окончания</div>
					<div class="col-3">КВ за продукт</div>
					<div class="col-3">Дата выплаты КВ</div>
				</div>

				<div class="input-group no-gutters">';
				if (empty($ofu_product))
				{
					$block .= '<div class="col-3"><input type="text" name="wl_ofu[blocks][][creation_date]" class="form-control ofu-block-creation-date" placeholder="Дата"></div>
					<div class="col-1"><input type="text" class="form-control ofu-block-months" placeholder="Мес."></div>
					<div class="col-2"><input type="text" name="wl_ofu[blocks][][end_date]" class="form-control ofu-block-end-date" placeholder="Дата"></div>
					<div class="col-3"><input type="text" name="wl_ofu[blocks][][profit]" class="form-control ofu-block-profit" placeholder="Сумма, р."></div>
					<div class="col-3"><input type="text" name="wl_ofu[blocks][][profit_date]" class="form-control ofu-block-profit-date" placeholder="Дата"></div>';
				}
				else
				{
					$block .= '<div class="col-3"><input type="text" name="wl_ofu[blocks][][creation_date]" class="form-control ofu-block-creation-date" placeholder="Дата" value="'.date('d.m.Y', $ofu_product->creation_date).'"></div>

					<div class="col-1"><input type="text" class="form-control ofu-block-months" placeholder="Мес."></div>

					<div class="col-2"><input type="text" name="wl_ofu[blocks][][end_date]" class="form-control ofu-block-end-date" placeholder="Дата" value="'.date('d.m.Y', $ofu_product->end_date).'"></div>

					<div class="col-3"><input type="text" name="wl_ofu[blocks][][profit]" class="form-control ofu-block-profit" placeholder="Сумма, р." value="'.$ofu_product->profit.'"></div>

					<div class="col-3"><input type="text" name="wl_ofu[blocks][][profit_date]" class="form-control ofu-block-profit-date" placeholder="Дата" value="'.date('d.m.Y', $ofu_product->profit_date).'"></div>';
				}
		$block .= '</div>

				<div class="input-group no-gutters">
					<div class="col-12 d-flex justify-content-end">
						<a href="javascript://" class="ofu-remove-block">Удалить рассчет</a>
					</div>
				</div>
			</div>
    	';

    	return $block;
    }
}
