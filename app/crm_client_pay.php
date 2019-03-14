<?php
/*
Для вывода HTML не вывел оплата дилера (уточнить что это у СБ)
Для вывода HTML не вывел оплата ПТС (уточнить что это у СБ и в каких случаях выводить)
*/

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_client_pay extends Model
{
    //
    protected $fillable = ['worklist_id','client_id','pay','date','debt','status'];

    public static function getHtml($array,$price=0)
    {	
    	$str = '<div class="container">';

    	$str.='<div class="input-group">';
	    	$str .= '<div class="col-6">';
	    		$str.='<h5>Оплата клиента</h5>';
	    		$str.= '<h2 id="wl_pay_client">';
	    			if($array->count())
	    				$str .= $array->sum('pay').' р.';
	    			else
	    				$str.= '0 р.';
	    		$str.='</h2>';
	    	$str .= '</div>';

	    	$str .= '<div class="col-6">';
	    		$str.='<h5>Стоимость автомобиля</h5>';
	    		$str.= '<h2 id="wl_pay_carprice" data-price="'.$price.'">';
	    				$str .= $price.' р.';
	    		$str.='</h2>';
	    	$str .= '</div>';
    	$str .='<div>';

    	$str .= '<div class="input-group info">
					<span class="col-3">Сумма платежа</span>
					<span class="col-3">Дата платежа</span>
					<span class="col-6">Сумма остатка</span>
				</div>';

		if($array->count()==0)://если ещё не было ни одной оплаты
			$str.='<div class="pay_content">';
			$str .= '<div class="item">
						<div class="input-group">
							<input type="text" class="form-control col-3" placeholder="" name="wl_pay_sum[]">
							<input type="text" class="form-control col-3" placeholder="" name="wl_pay_date[]">
							<input type="text" class="form-control col-3" name="wl_pay_debt[]">
							<div class="col-1 d-flex align-items-center">
								<span><input type="checkbox" name="wl_pay_status[]" value="1"></span>
							</div>
							<div class="col-1 d-flex align-items-center">
								<a href="javascript://" class="text-danger"><i class="fas fa-times"></i></a>
							</div>
							<div class="col-1 d-flex align-items-center"><a id="adder_pay"><i class="fa fa-plus"></i></a></div>
						</div>
					</div>';
			$str.='</div>';	
		else:
			$str.='<div class="pay_content">';
			foreach ($array as $key => $value) ://если оплата была хотя бы одна
				if($key==0)
					$str.='<div class="item">';
				else
					$str.='<div>';

				/////////////////////////////////////////////////	
					$str .= '<div class="input-group">';
						$str.='<div class="col-3">';
							$str.='<input type="text" class="form-control " placeholder="" name="wl_pay_sum[]" value="'.$value->pay.'">';
						$str .='</div>';
						
						$str.='<div class="col-3">';	
							$str.='<input type="text" class="form-control " placeholder="" name="wl_pay_date[]" value="'.date('d.m.Y',$value->date).'" >';
						$str .='</div>';

						$str.='<div class="col-3">';
							$str.='<input type="text" class="form-control " name="wl_pay_debt[]" value="'.$value->debt.'" >';
						$str .='</div>';

						$str.='<div class="col-1 d-flex align-items-center">';
							if($value->status)
								$str.='<input type="checkbox" checked name="wl_pay_status[]" value="1">';
							else
								$str.='<input type="checkbox"  name="wl_pay_status[]" value="1">';
						$str.='</div>';

						$str.='<div class="col-1 d-flex align-items-center">';
							$str.='<a href="javascript://" class="text-danger"><i class="fas fa-times"></i></a>';
						$str.='</div>';

						if($key==0)
							$str.='<div class="col-1 d-flex align-items-center"><a id="adder_pay"><i class="fa fa-plus"></i></a></div>';

					$str.='</div>';
				///////////////////////////////////////////////////

				$str.='</div>';
			endforeach;
			$str.='</div>';	
		endif;

			$str.='<div class="input-group info">';
		    	$str .= '<div class="col-6">';
		    		$str.='<h5>Оплата дилера</h5>';
		    		$str.= '<h2 id="wl_pay_diler">';
		    			$str.='0 р.';
		    		$str.='</h2>';
		    	$str .= '</div>';
	    	$str .='</div>';

	    	$str .='<div class="clearfix"></div>';

			$str .= '<div class="input-group info">
						<span class="col-12">График оплаты ПТС
							<a id="adder_pay_pts" style="display:inline-block; magin-left:20px; "><i class="fa fa-plus"></i></a>
						</span>
						<div class="col-3 pay_pts_content item">
							<input type="text" class="form-control " >							
						</div>
					</div>';
		$str.='</div>';

		return $str;
    }
}


