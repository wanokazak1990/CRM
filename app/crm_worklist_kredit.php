<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_worklist_kredit extends Model
{
    //
    protected $fillable = ['worklist_id', 'type_id', 'payment', 'valid_date', 'margin_kredit', 'margin_product', 'margin_other'];

    public function getWaitings()
    {
    	return $this->hasMany('App\crm_worklist_kwaiting','kredit_id','id');
    }

    public function getHtml()
    {
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
					<select class="form-control col-3">
						<option selected="" disabled="" name="wl_kredit[type_id]">Способ оплаты</option>
					</select>
					<input type="text" class="form-control col-3 kredit_payment" name="wl_kredit[payment]">
					<input type="text" class="form-control col-3 kredit_price" name="wl_kredit[price]">
					<input type="text" class="form-control col-3 kredit_sum" name="wl_kredit[sum]">
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
			<div class="col-12 kredit_app_content">
				<div class="item app_block">
					<div class="input-group ">
						<label class="col-3">Консультант</label>
						<label class="col-3">Кредитор</label>
						<label class="col-3">Первый взнос</label>
						<label class="col-3">Сумма кредита</label>
					</div>
					<div class="input-group ">
						<select class="form-control col-3" name="wl_kredit[app][0][author_id]">
							<option selected="" disabled="">Консультант</option>
						</select>
						<select class="form-control col-3" name="wl_kredit[app][0][kreditor_id]">
							<option selected="" disabled="">Кредитор</option>
						</select>
						<input type="text" class="form-control col-3" name="wl_kredit[app][0][payment]">
						<input type="text" class="form-control col-3" name="wl_kredit[app][0][sum]">
					</div>

					<div class="input-group ">
						<label class="col-3">Дата заявки</label>
						<label class="col-3">Дата одобрения</label>
						<label class="col-3">Срок действия</label>
						<label class="col-3">Дата оформления</label>
					</div>
					<div class="input-group ">
						<input type="text" class="form-control col-3" name="wl_kredit[app][0][date_in]">
						<select class="form-control col-3" name="wl_kredit[app][0][status_id]">
							<option selected="" disabled="">Одобрен</option>
						</select>										
						<input type="text" class="form-control col-1" name="wl_kredit[app][0][day_count]">
						<input type="text" class="form-control col-2" name="wl_kredit[app][0][date_action]">										
						<input type="text" class="form-control col-3" name="wl_kredit[app][0][date_offer]">
					</div>

					<div class="col-12">
						<div class="row">
							<div class="col-8">
								<label><input type="checkbox" name="wl_kredit[app][0][product][0]" value="1">КАСКО</label>
								<label><input type="checkbox" name="wl_kredit[app][0][product][1]" value="1">GAP</label>
								<label><input type="checkbox" name="wl_kredit[app][0][product][2]" value="1">СЖ</label>
							</div>
							<div class="col-4 text-right">
								<a class="deleter_app">Удалить заявку</a>
							</div>
						</div>
					</div>
				</div>
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
					<input type="text" class="form-control col-3" name="wl_kredit[valid_date]">
					<input type="text" class="form-control col-3" name="wl_kredit[margin_kredit]">
					<input type="text" class="form-control col-3" name="wl_kredit[margin_product]">										
					<input type="text" class="form-control col-3" name="wl_kredit[margin_other]">
				</div>
			</div>
    	';

    	return $str;
    }
}
