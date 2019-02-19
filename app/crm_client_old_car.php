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
			<div class="input-group">
				<div class="col-3"><input type="radio" name="cc[status]" value="1" <?=($this->status==1)?'checked':'';?>> Нет авто</div>
				<div class="col-3"><input type="radio" name="cc[status]" value="2" <?=($this->status==2)?'checked':'';?>> Есть авто</div>
				<div class="col-3"><input type="radio" name="cc[status]" value="3" <?=($this->status==3)?'checked':'';?>> Авто в ТИ</div>
				<div class="col-3"><input type="radio" name="cc[status]" value="4" <?=($this->status==4)?'checked':'';?>> Авто в УТ</div>
			</div>
			<div class="input-group">
				<input type="text" class="col-3 form-control" name="cc[brand]" 	placeholder="Марка" value="<?=$this->brand;?>">
				<input type="text" class="col-3 form-control" name="cc[model]" 	placeholder="Модель" value="<?=$this->model;?>">
				<input type="text" class="col-3 form-control" name="cc[year]" 	placeholder="Год по ПТС" value="<?=$this->year;?>">
				<input type="text" class="col-3 form-control" name="cc[mileage]" placeholder="Пробег" value="<?=$this->mileage;?>">
			</div>

			<div class="input-group">
				<button type="button" class="col-3 offset-6 btn btn-success oldcar-photo">
					Фотографии
				</button>
				<button type="button" class="col-3 btn btn-primary">
					Отдать на оценку
				</button>
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

			<div class="input-group">
				<div class="col-3 text-center">
					<label>Автомобиль</label>
				</div>
				<div class="col-3 text-center">
					<label>Анализ рынка</label>
				</div>
				<div class="col-3 text-center">
					<label>Акт осмотра</label>
				</div>
				<div class="col-3 text-center">
					<label>Акт диагностики</label>
				</div>
			</div>
			<?php
			
				foreach ($carModel as $key => $model) :

			?>
			<div id="cc_analysis">
				<div class="cc_analysis_block">
					<div class="input-group">
						<div class="col-3 d-flex align-items-center justify-content-end">
							<a href="#"><i class="fas fa-times"></i></a>
						</div>
						<div class="col-3 text-center">
							<a href="#" class="btn btn-primary"><i class="fas fa-clipboard-list"></i></a>
						</div>
						<div class="col-3 text-center">
							<a href="#" class="btn btn-primary"><i class="fas fa-clipboard-list"></i></a>
						</div>
						<div class="col-3 text-center">
							<a href="#" class="btn btn-primary disabled"><i class="fas fa-clipboard-list"></i></a>
						</div>
					</div>
					<div class="input-group">
						<input type="text" class="col-3 form-control text-center" value="<?=$model->name;?>" disabled>
						<input type="text" class="col-3 form-control text-center" value="" disabled>
						<input type="text" class="col-3 form-control text-center" value="" disabled>
						<input type="text" class="col-3 form-control text-center" value="" disabled>
					</div>
				</div>
			</div>
		<?php
			endforeach;
		endif;
	}
}