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
    		<div class="col-12">
				<h5 class="">Выявленные потребности:</h5>
				<div class="input-group ">
					<label class="col-3">Способ оплаты</label>
					<label class="col-3">Первый взнос</label>
					<label class="col-3">Цена продажи</label>
					<label class="col-3">Сумма кредита</label>
				</div>
				<div class="input-group ">
					<select class="form-control col-3" name="wl_kredit[type_id]">
						<option selected="" disabled="" value="0" ">Способ оплаты</option>';
						foreach ($payType as $key => $value) {
							$select = '';
							if($key==$this->type_id) $select = 'selected';
							$str .= "<option {$select} value='{$key}'>{$value}</option>";
						}
		$str.='
					</select>
					<input type="text" class="form-control col- money3 kredit_payment" name="wl_kredit[payment]">
					<input type="text" class="form-control col-3 money kredit_price" name="wl_kredit[price]">
					<input type="text" class="form-control col-3 money kredit_sum" name="wl_kredit[sum]">
				</div>
			</div>

			<div class="col-12 pdt-20">
				<h5 class="">Расчётная маржа КС:</h5>
				<h2 class="">XX XXX р.</h2>
			</div>

			<hr/>

			<div class="col-12">
				<div class="row">
					<div class="col-8">
						<h5 class="">Заявки на кредитную сделку:</h5>
					</div>
					<div class="col-4">
						<h5 class="text-right" style="padding-right:10px;">
							<a id="adder_app"><i class="fa fa-plus"></i></a>
						</h5>
					</div>
				</div>
			</div>

			<!--СТАРТОВЫЙ БЛОК ПАРАМЕТРЫ ЗАЯВКИ НАЧАЛО-->
			<div class="col-12 kredit_app_content">';
			if($this->getWaitings->count()) : 
				foreach ($this->getWaitings as $key => $item) :	
					$install = explode('|',$item->product);	
					$status_date = 'Дата одобрения';
					if($item->status_date)
						$status_date = date('d.m.Y',$item->status_date);
					$str .= '
						<div class="item app_block">
							<div class="input-group ">
								<label class="col-3">Консультант</label>
								<label class="col-3">Кредитор</label>
								<label class="col-3">Первый взнос</label>
								<label class="col-3">Сумма кредита</label>
							</div>
							<div class="input-group ">
								<select class="form-control col-3" name="wl_kredit[app][0][author_id]">
									<option selected="" disabled="">Консультант</option>';
									foreach ($author as $key => $value) {
										$select = '';
										if($key==$item->author_id) $select='selected';										
										$str .= "<option {$select} value='{$key}'>{$value}</option>";
									}	
						$str.='
								</select>
								<select class="form-control col-3" name="wl_kredit[app][0][kreditor_id]">
									<option selected="" disabled="">Кредитор</option>';
									foreach ($kreditorOpt as $key => $value) {
										$select = '';
										if($key==$item->kreditor_id) $select='selected';										
										$str .= "<option {$select} value='{$key}'>{$value}</option>";
									}										
						$str .= '</select>
								<input type="text" class="form-control col-3 money" name="wl_kredit[app][0][payment]" value="'.number_format($item->payment,0,'',' ').'">
								<input type="text" class="form-control col-3 money" name="wl_kredit[app][0][sum]" value="'.number_format($item->sum,0,'',' ').'">
							</div>

							<div class="input-group ">
								<label class="col-3">Дата заявки</label>
								<input type="text" class="col-3 input-label status_date" name="wl_kredit[app][0][status_date]" value="'.$status_date.'" >
								<label class="col-3">Срок действия</label>
								<label class="col-3">Дата оформления</label>
							</div>
							<div class="input-group ">
								<input type="text" class="form-control col-3 calendar" name="wl_kredit[app][0][date_in]" value="'.date('d.m.Y',$item->date_in).'">
								<select class="form-control col-3 status" name="wl_kredit[app][0][status_id]">
									<option selected="" disabled="">Ожидает действие</option>';
									foreach ($item->arrayKreditResult() as $key => $value) {
										$select = '';
										if($key == $item->status_id) $select = 'selected';
										$str.= "<option value='{$key}'>{$value}</option>";
									}
								$str .='</select>										
								<input type="text" class="form-control col-1 action_mon" name="wl_kredit[app][0][day_count]" value="'.$item->day_count.'">
								<input type="text" class="form-control col-2 action_date calendar " name="wl_kredit[app][0][date_action]" value="'.date('d.m.Y',$item->date_action).'">	
								<input type="text" class="form-control col-3 calendar" name="wl_kredit[app][0][date_offer]" value="'.date('d.m.Y',$item->date_offer).'">
							</div>

							<div class="col-12">
								<div class="row">
									<div class="col-8">';						
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
									<div class="col-4 text-right">
										<a class="deleter_app">Удалить заявку</a>
									</div>
								</div>
							</div>
						</div>';
				endforeach;
			else:
				$waiting = new crm_worklist_kwaiting();
				$str .= '
						<div class="item app_block">
							<div class="input-group ">
								<label class="col-3">Консультант</label>
								<label class="col-3">Кредитор</label>
								<label class="col-3">Первый взнос</label>
								<label class="col-3">Сумма кредита</label>
							</div>
							<div class="input-group ">
								<select class="form-control col-3" name="wl_kredit[app][0][author_id]">
									<option selected="" disabled="">Консультант</option>';
									foreach ($author as $key => $value) {							
										$str .= "<option value='{$key}'>{$value}</option>";
									}	
						$str .='
								</select>
								<select class="form-control col-3" name="wl_kredit[app][0][kreditor_id]">
									<option selected="" disabled="">Кредитор</option>';
									foreach ($kreditorOpt as $key => $value) {							
										$str .= "<option value='{$key}'>{$value}</option>";
									}										
						$str .= '</select>
								<input type="text" class="form-control col-3 money" name="wl_kredit[app][0][payment]" >
								<input type="text" class="form-control col-3 money" name="wl_kredit[app][0][sum]" >
							</div>

							<div class="input-group ">
								<label class="col-3">Дата заявки</label>
								<input type="text" class="col-3 input-label status_date" name="wl_kredit[app][0][status_date]" value="Дата одобрения" >
								<label class="col-3">Срок действия</label>
								<label class="col-3">Дата оформления</label>
							</div>
							<div class="input-group ">
								<input type="text" class="form-control col-3 calendar" name="wl_kredit[app][0][date_in]"  >
								<select class="form-control col-3 status" name="wl_kredit[app][0][status_id]">
									<option selected="" disabled="">Ожидает действия</option>';
									foreach ($waiting->arrayKreditResult() as $key => $value) {
										$str.= "<option value='{$key}'>{$value}</option>";
									}
								$str.='</select>										
								<input type="text" class="form-control col-1" name="wl_kredit[app][0][day_count]"  >
								<input type="text" class="form-control col-2 calendar" name="wl_kredit[app][0][date_action]"  >	
								<input type="text" class="form-control col-3 calendar" name="wl_kredit[app][0][date_offer]"  >
							</div>

							<div class="col-12">
								<div class="row">
									<div class="col-8">';
										foreach ($products as $key => $value) {
											$str .= '<label>
												<input type="checkbox" name="wl_kredit[app][0][product]['.$key.']" value="'.$key.'">'.
												$value.
											'</label>';
										}
									$str .= '</div>
									<div class="col-4 text-right">
										<a class="deleter_app">Удалить заявку</a>
									</div>
								</div>
							</div>
						</div>';
			endif;
			$str .= '
			</div>
			<!--СТАРТОВЫЙ БЛОК ПАРАМЕТРЫ КРЕДИТА КОНЕЦ-->

			<div class="col-12 pdt-20">
				<div class="row">
					<div class="col-6 text-left">
						<h5>Доходность КС:</h5>
						<h2>X%</h2>
					</div>
					<div class="col-6 text-right">
						<h5>Маржа КС:</h5>
						<h2>XX XXX р.</h2>
					</div>
				</div>
			</div>

			<div class="col-12 pdt-20">
				<div class="input-group">
					<label class="col-3">Дата валидации</label>
					<label class="col-3">КВ за кредит</label>
					<label class="col-3">КВ за продукты</label>
					<label class="col-3">КВ прочее</label>
				</div>
				<div class="input-group">
					<input type="text" class="form-control col-3  calendar" name="wl_kredit[valid_date]">
					<input type="text" class="form-control col-3 money" name="wl_kredit[margin_kredit]">
					<input type="text" class="form-control col-3 money" name="wl_kredit[margin_product]">										
					<input type="text" class="form-control col-3 money" name="wl_kredit[margin_other]">
				</div>
			</div>
    	';

    	return $str;
    }
}
