<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_worklist_kredit extends Model
{
    //
    protected $fillable = ['worklist_id', 'type_id', 'payment', 'valid_date', 'margin_kredit', 'margin_product', 'margin_other'];

    public function totalMargin()
    {
    	return $this->margin_product+$this->margin_kredit+$this->margin_other;
    }
    public function getWaitings()
    {
    	return $this->hasMany('App\crm_worklist_kwaiting','kredit_id','id');
    }

    public function getAktualWaiting()
    {
    	return $this->hasOne('App\crm_worklist_kwaiting','kredit_id','id')->where('date_offer','<>',0);
    }

    public function getHtml()
    {
    	$kreditorOpt = \App\crm_partner::where('type',1)->pluck('short_name','id');
    	$author = \App\user::pluck('name','id');
  		$payType = \App\pay_type::pluck('name','id');
  		$products = \App\kredit_product::pluck('name','id');


    	$needs = crm_need_car::where('worklist_id',$this->worklist_id)->first();
    	if(!$this->payment)
    		$this->payment = isset($needs->firstpay)?$needs->firstpay:0;
    	if(!$this->type_id)
    		$this->type_id = isset($needs->pay_type)?$needs->pay_type:0;
    	


    	$str ='
    		
				<h5 class="">Выявленные потребности:</h5>
				<div class="input-group no-gutters">
					<div class="col-3">Способ оплаты</div>
					<div class="col-3">Первый взнос</div>
					<div class="col-3">Цена продажи</div>
					<div class="col-3">Сумма кредита</div>
				</div>
				<div class="input-group no-gutters">
					<div class="col-3"><select class="form-control" name="wl_kredit[type_id]">
						<option selected="" disabled="" value="0" ">Способ оплаты</option>';
						foreach ($payType as $key => $value) {
							$select = '';
							if($key==$this->type_id) $select = 'selected';
							$str .= "<option {$select} value='{$key}'>{$value}</option>";
						}
		$str.='
					</select></div>
					<div class="col-3"><input type="text" class="form-control money kredit_payment" name="wl_kredit[payment]"></div>
					<div class="col-3"><input type="text" class="form-control money kredit_price" name="wl_kredit[price]"></div>
					<div class="col-3"><input type="text" class="form-control money kredit_sum" name="wl_kredit[sum]"></div>
				</div>
			

			<div class="input-group no-gutters pt-3">
				<div class="col-12">
					<h5 class="">Расчётная маржа КС:</h5>
				</div>
				<div class="col-12">
					<h2 class="">XX XXX р.</h2>
				</div>
			</div>

			<hr>
			
			<div class="input-group no-gutters d-flex justify-content-between">
				<h5>Заявки на кредитную сделку:</h5>
				<h5 style="padding-right:10px;">
					<a href="javascript://" id="adder_app"><i class="icofont-plus-circle"></i></a>
				</h5>
			</div>
			
			<!-- СТАРТОВЫЙ БЛОК ПАРАМЕТРЫ ЗАЯВКИ НАЧАЛО -->
			<div class="kredit_app_content">';
			if ($this->getWaitings->count()) : 
				foreach ($this->getWaitings as $key => $item) :	
					$install = explode('|', $item->product);	
					$status_date = 'Дата одобрения';
					if ($item->status_date)
						$status_date = date('d.m.Y', $item->status_date);
					$str .= '
						<div class="item app_block">
							<div class="input-group no-gutters">
								<div class="col-3">Консультант</div>
								<div class="col-3">Кредитор</div>
								<div class="col-3">Первый взнос</div>
								<div class="col-3">Сумма кредита</div>
							</div>
							<div class="input-group no-gutters">
								<div class="col-3"><select class="form-control" name="wl_kredit[app][0][author_id]">
									<option selected="" disabled="">Консультант</option>';
									foreach ($author as $key => $value) {
										$select = '';
										if($key==$item->author_id) $select='selected';										
										$str .= "<option {$select} value='{$key}'>{$value}</option>";
									}	
						$str.='
								</select></div>
								<div class="col-3"><select class="form-control" name="wl_kredit[app][0][kreditor_id]">
									<option selected="" disabled="">Кредитор</option>';
									foreach ($kreditorOpt as $key => $value) {
										$select = '';
										if($key==$item->kreditor_id) $select='selected';										
										$str .= "<option {$select} value='{$key}'>{$value}</option>";
									}										
						$str .= '</select></div>
								<div class="col-3"><input type="text" class="form-control money" name="wl_kredit[app][0][payment]" value="'.number_format($item->payment,0,'',' ').'"></div>
								<div class="col-3"><input type="text" class="form-control money" name="wl_kredit[app][0][sum]" value="'.number_format($item->sum,0,'',' ').'"></div>
							</div>

							<div class="input-group no-gutters">
								<div class="col-3">Дата заявки</div>
								<div class="col-3"><input type="text" class="input-label status_date" name="wl_kredit[app][0][status_date]" value="'.$status_date.'" ></div>
								<div class="col-3">Срок действия</div>
								<div class="col-3">Дата оформления</div>
							</div>
							<div class="input-group no-gutters">
								<div class="col-3"><input type="text" class="form-control calendar" name="wl_kredit[app][0][date_in]" value="'.date('d.m.Y',$item->date_in).'"></div>
								<div class="col-3"><select class="form-control status" name="wl_kredit[app][0][status_id]">
									<option selected="" disabled="">Ожидает действие</option>';
									foreach ($item->arrayKreditResult() as $key => $value) {
										$select = '';
										if($key == $item->status_id) $select = 'selected';
										$str.= "<option value='{$key}'>{$value}</option>";
									}
								$str .='</select></div>									
								<div class="col-1"><input type="text" class="form-control action_mon" name="wl_kredit[app][0][day_count]" value="'.$item->day_count.'"></div>
								<div class="col-2"><input type="text" class="form-control action_date calendar " name="wl_kredit[app][0][date_action]" value="'.date('d.m.Y',$item->date_action).'"></div>
								<div class="col-3"><input type="text" class="form-control calendar" name="wl_kredit[app][0][date_offer]" value="'.date('d.m.Y',$item->date_offer).'"></div>
							</div>

							<div class="input-group no-gutters d-flex justify-content-between">
								<div>';						
									foreach ($products as $key => $value) {
										$check = '';
										if(in_array($key, $install)) $check = 'checked';
										$str .= '<label>
											<input '.$check.' type="checkbox" name="wl_kredit[app][0][product]['.$key.']" value="'.$key.'">'.
											$value.
										'</label>';
									}
								$str.='
								</div>
								<div>
									<a href="javascript://" class="deleter_app default-link">Удалить заявку</a>
								</div>
							</div>
							
						</div>';
				endforeach;
			else:
				$waiting = new crm_worklist_kwaiting();
				$str .= '
						<div class="item app_block">
							<div class="input-group no-gutters">
								<div class="col-3">Консультант</div>
								<div class="col-3">Кредитор</div>
								<div class="col-3">Первый взнос</div>
								<div class="col-3">Сумма кредита</div>
							</div>
							<div class="input-group no-gutters">
								<div class="col-3"><select class="form-control" name="wl_kredit[app][0][author_id]">
									<option selected="" disabled="">Консультант</option>';
									foreach ($author as $key => $value) {							
										$str .= "<option value='{$key}'>{$value}</option>";
									}	
						$str .='
								</select></div>
								<div class="col-3"><select class="form-control" name="wl_kredit[app][0][kreditor_id]">
									<option selected="" disabled="">Кредитор</option>';
									foreach ($kreditorOpt as $key => $value) {							
										$str .= "<option value='{$key}'>{$value}</option>";
									}										
						$str .= '</select></div>
								<div class="col-3"><input type="text" class="form-control money" name="wl_kredit[app][0][payment]" ></div>
								<div class="col-3"><input type="text" class="form-control money" name="wl_kredit[app][0][sum]" ></div>
							</div>

							<div class="input-group no-gutters">
								<div class="col-3">Дата заявки</div>
								<div class="col-3"><input type="text" class="input-label status_date" name="wl_kredit[app][0][status_date]" value="Дата одобрения" ></div>
								<div class="col-3">Срок действия</div>
								<div class="col-3">Дата оформления</div>
							</div>
							<div class="input-group no-gutters">
								<div class="col-3"><input type="text" class="form-control calendar" name="wl_kredit[app][0][date_in]"  ></div>
								<div class="col-3"><select class="form-control status" name="wl_kredit[app][0][status_id]">
									<option selected="" disabled="">Ожидает действия</option>';
									foreach ($waiting->arrayKreditResult() as $key => $value) {
										$str.= "<option value='{$key}'>{$value}</option>";
									}
								$str.='</select></div>									
								<div class="col-1"><input type="text" class="form-control" name="wl_kredit[app][0][day_count]"  ></div>
								<div class="col-2"><input type="text" class="form-control calendar" name="wl_kredit[app][0][date_action]"  ></div>	
								<div class="col-3"><input type="text" class="form-control calendar" name="wl_kredit[app][0][date_offer]"  ></div>
							</div>

							
							<div class="input-group no-gutters d-flex justify-content-between">
								<div>';
									foreach ($products as $key => $value) {
										$str .= '<label class="mr-2">
											<input type="checkbox" class="mr-1" name="wl_kredit[app][0][product]['.$key.']" value="'.$key.'">'.
											$value.
										'</label>';
									}
								$str .= '</div>
								<div>
									<a href="javascript://" class="deleter_app default-link">Удалить заявку</a>
								</div>
							</div>
							
						</div>';
			endif;
			$str .= '
			</div>
			<!--СТАРТОВЫЙ БЛОК ПАРАМЕТРЫ КРЕДИТА КОНЕЦ-->

			<div class="input-group no-gutters pt-3 d-flex justify-content-between">
				<div>
					<h5>Доходность КС:</h5>
					<h2>X%</h2>
				</div>
				<div>
					<h5>Маржа КС:</h5>
					<h2>XX XXX р.</h2>
				</div>
			</div>			

			<div class="input-group no-gutters pt-3">
				<div class="col-3">Дата валидации</div>
				<div class="col-3">КВ за кредит</div>
				<div class="col-3">КВ за продукты</div>
				<div class="col-3">КВ прочее</div>
			</div>
			<div class="input-group no-gutters">
				<div class="col-3"><input type="text" class="form-control calendar" name="wl_kredit[valid_date]"></div>
				<div class="col-3"><input type="text" class="form-control money" name="wl_kredit[margin_kredit]"></div>
				<div class="col-3"><input type="text" class="form-control money" name="wl_kredit[margin_product]"></div>
				<div class="col-3"><input type="text" class="form-control money" name="wl_kredit[margin_other]"></div>
			</div>
    	';

    	return $str;
    }
}
