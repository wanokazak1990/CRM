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
	    			$blocks .= self::createHtmlBlock($product);
	    		}

	    		return $blocks;
	    	}
	    	else
    			return null;
    	}
    	else
    		return self::createHtmlBlock();
    }

    public static function sumProfit($worklistId)
    {
    	return self::where('worklist_id',$worklistId)->sum('profit');
    }

    public static function createHtmlBlock($ofu_product = [])
    {
    	$users = user::pluck('name', 'id');
    	$partners = crm_partner::where('brand_id', 1)->pluck('name', 'id');
    	$products = company::where('razdel', 3)->pluck('name', 'id');
		
		$block = '
    		<div class="my-2 ofu-block">
				<div class="input-group">
					<span class="col-3">Консультант</span>
					<span class="col-3">Продукт</span>
					<span class="col-3">Партнер</span>
					<span class="col-3">Стоимость продукта</span>
				</div>

				<div class="input-group mb-1">
					<select class="col-3 form-control ofu-block-authors">';
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

					<select class="col-3 form-control ofu-block-products">';
					if (empty($ofu_product))
						$block .= '<option selected disabled>Укажите параметр</option>';
					else
						$block .= '<option disabled>Укажите параметр</option>';

					foreach ($products as $id => $product) 
					{
						if (empty($ofu_product))
							$block .= '<option value="'.$id.'">'.$product.'</option>';
						else
						{
							if ($ofu_product->author_id == $id)
								$block .= '<option value="'.$id.'" selected>'.$product.'</option>';
							else
								$block .= '<option value="'.$id.'">'.$product.'</option>';
						}
					}

		$block .= '</select>

					<select class="col-3 form-control ofu-block-partners">';
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
							if ($ofu_product->author_id == $id)
								$block .= '<option value="'.$id.'" selected>'.$partner.'</option>';
							else
								$block .= '<option value="'.$id.'">'.$partner.'</option>';
						}
					}

		$block .= '</select>';
				
				if (empty($ofu_product))
					$block .= '<input type="text" class="col-3 form-control ofu-block-price" placeholder="Стоимость, р.">';
				else
					$block .= '<input type="text" class="col-3 form-control ofu-block-price" placeholder="Стоимость, р." value="'.$ofu_product->price.'">';
		
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
					$block .= '<input type="text" class="col-3 form-control ofu-block-creation-date" placeholder="Дата">
					<input type="text" class="col-1 form-control ofu-block-months" placeholder="Мес.">
					<input type="text" class="col-2 form-control ofu-block-end-date" placeholder="Дата">
					<input type="text" class="col-3 form-control ofu-block-profit" placeholder="Сумма, р.">
					<input type="text" class="col-3 form-control ofu-block-profit-date" placeholder="Дата">';
				}
				else
				{
					$block .= '<input type="text" class="col-3 form-control ofu-block-creation-date" placeholder="Дата" value="'.date('d.m.Y', $ofu_product->creation_date).'">

					<input type="text" class="col-1 form-control ofu-block-months" placeholder="Мес.">

					<input type="text" class="col-2 form-control ofu-block-end-date" placeholder="Дата" value="'.date('d.m.Y', $ofu_product->end_date).'">

					<input type="text" class="col-3 form-control ofu-block-profit" placeholder="Сумма, р." value="'.$ofu_product->profit.'">

					<input type="text" class="col-3 form-control ofu-block-profit-date" placeholder="Дата" value="'.date('d.m.Y', $ofu_product->profit_date).'">';
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
