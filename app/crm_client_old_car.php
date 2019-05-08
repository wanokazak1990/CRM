<?php

namespace App;
use Storage;

use Illuminate\Database\Eloquent\Model;

class crm_client_old_car extends Model
{
    //
    protected $fillable = [
    	'client_id', 
    	'worklist_id', 
    	'cc_status', 
    	'cc_brand', 
    	'cc_model', 
    	'cc_year', 
    	'cc_mileage'
    ];

    public static function checkOnOldCar($worklist_id)
    {
    	$oldCar = crm_client_old_car::where('worklist_id',$worklist_id)->first();
    	if($oldCar)
    		return $oldCar;
    	$oldCar = new crm_client_old_car();
    	$oldCar->worklist_id = $worklist_id;
    	return $oldCar;
    }

    public function saveOldCar($data,$worklist)
    {
        foreach ($data as $key => $item) 
            $this->$key = $item;
        $this->client_id = $worklist->client_id;
        $this->worklist_id = $worklist->id;
        if($this->status)
        	$this->save();
        if($this->id)
        	return true;
        return false;
    }

    public function myWorkList()
    {
    	$worklist = crm_worklist::where('id',$this->worklist_id)->first();
    	return $worklist;
    }




	public function clientCarHtml($carModel = array())
	{
		$carModel = $this->myWorkList()->selectedModel();
		?>	
			<div class="input-group no-gutters">
				<div class="col-3"><input type="radio" name="cc[status]" value="1" <?=($this->status==1)?'checked':'';?>> Нет авто</div>
				<div class="col-3"><input type="radio" name="cc[status]" value="2" <?=($this->status==2)?'checked':'';?>> Есть авто</div>
				<div class="col-3"><input type="radio" name="cc[status]" value="3" <?=($this->status==3)?'checked':'';?>> Авто в ТИ</div>
				<div class="col-3"><input type="radio" name="cc[status]" value="4" <?=($this->status==4)?'checked':'';?>> Авто в УТ</div>
			</div>
			<div class="input-group no-gutters">
				<div class="col-3">
					<input type="text" class="form-control" name="cc[brand]" placeholder="Марка" value="<?=$this->brand;?>">
				</div>
				<div class="col-3">
					<input type="text" class="form-control" name="cc[model]" placeholder="Модель" value="<?=$this->model;?>">
				</div>
				<div class="col-3">
					<input type="text" class="form-control" name="cc[year]" placeholder="Год по ПТС" value="<?=$this->year;?>">
				</div>
				<div class="col-3">
					<input type="text" class="form-control" name="cc[mileage]" placeholder="Пробег" value="<?=$this->mileage;?>">
				</div>
			</div>

			<div class="input-group no-gutters pb-3">
				<div class="col-3 offset-6">
					<button type="button" class="btn btn-success btn-block oldcar-photo">Фотографии</button>
				</div>
				<div class="col-3">
					<button type="button" class="btn btn-primary btn-block">Отдать на оценку</button>
				</div>
			</div>

			<div class="photo-load">
				<a class="close fa fa-times"></a>
				<h3>Загрузите фото автомобиля клиента:</h3>
				<div class="old-photo">
					<?php 
						$files = Storage::disk('public')->files('worklist/'.$this->worklist_id.'/oldcar');
						foreach ($files as $key => $value) 
						{
						?>
							<img src="<?=Storage::url($value);?>">
						<?php	
						}

					?>
				</div>
				<div class="text-right">
					
					<input type="file" name="photo" multiple style="display: none;">

					<button class="btn btn-success search-photo" type="button">Выбор фото</button>
					<button class="btn btn-primary load" type="button">Загрузить выбранное</button>
				</div>
			</div>

		<?php

		if(!empty($carModel)) : 
		
		?>

			<div class="input-group no-gutters d-flex align-items-center">
				<div class="col-3 text-center">Автомобиль</div>
				<div class="col-3 text-center">Анализ рынка</div>
				<div class="col-3 text-center">Акт осмотра</div>
				<div class="col-3 text-center">Акт диагностики</div>
			</div>
			<?php
			
				foreach ($carModel as $key => $model) :

			?>
			<div id="cc_analysis">
				<div class="cc_analysis_block">
					<div class="input-group">
						<div class="col-3 d-flex align-items-center justify-content-end">
							<a href="#"><i class="icofont-close"></i></a>
						</div>
						<div class="col-3 text-center">
							<a href="#" class="btn btn-primary"><i class="icofont-ui-file"></i></a>
						</div>
						<div class="col-3 text-center">
							<a href="#" class="btn btn-primary"><i class="icofont-ui-file"></i></a>
						</div>
						<div class="col-3 text-center">
							<a href="#" class="btn btn-primary disabled"><i class="icofont-ui-file"></i></a>
						</div>
					</div>
					<div class="input-group no-gutters">
						<div class="col-3">
							<input type="text" class="form-control text-center" value="<?=$model->name;?>" disabled>
						</div>
						<div class="col-3">
							<input type="text" class="form-control text-center" value="" disabled>
						</div>
						<div class="col-3">
							<input type="text" class="form-control text-center" value="" disabled>
						</div>
						<div class="col-3">
							<input type="text" class="form-control text-center" value="" disabled>
						</div>
					</div>
				</div>
			</div>
		<?php
			endforeach;
		endif;
	}
}