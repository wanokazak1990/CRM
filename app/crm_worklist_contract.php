<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_worklist_contract extends Model
{
    //
    protected $fillable = [
    	'worklist_id', 'client_id', 'author_id', 'number', 'date', 
    	'date_crash', 'status', 'crash', 'close_author', 'close_date_issue', 
    	'close_date_sale', 'close_date_offs'
    ];

    public function shipdateAll()
    {
    	return $this->hasMany('App\crm_contract_ship', 'contract_id', 'id');
    }
    public function shipdateLast()
    {
    	return $this->hasOne('App\crm_contract_ship', 'contract_id', 'id')->orderBy('id', 'DESC');
    }

    public function getDate($date)
    {
    	if (strlen($date) > 6)
    		return date('d.m.Y', $date);
    }

    public function getAuthor()
    {
    	return $this->hasOne('App\user', 'id', 'author_id');
    }

    public function getCloser()
    {
    	return $this->hasOne('App\user', 'id', 'close_author');
    }

    public function getHtml($crash = 0, $status = 0, $disabled = 'disabled')
    {

    	if ($this->status) 
    		$status = 'checked';
    	
    	if ($this->crash) 
    	{
    		$crash = 'checked';
    		$disabled = '';
    	}

	    $str = '<h3>Контракт:</h3>';
	    	$str .= '<div class="input-group no-gutters">
				<div class="col-3">Оформитель</div>
				<div class="col-3">Номер договора</div>
				<div class="col-3">Дата договора</div>
				<div class="col-3 d-flex justify-content-between">
					Расторжение
					<input type="checkbox" name="contract[crash]" value="1" '.$crash.' >
				</div>
			</div>';

			$str .= '<div class="input-group no-gutters">';
				$str.= '<div class="col-3"><select class="form-control" name="contract[author_id]">';
					$str .= '<option selected disabled>Выберите автора</option>';
					foreach (user::pluck('name', 'id') as $key => $value) :
						if ($key == $this->author_id) 
							$check = 'selected'; 
						else 
							$check = '';
						$str .= "<option {$check} value='{$key}'>{$value}</option>";
					endforeach;
				$str .= '</select></div>';

				$str .= '<div class="col-3"><input type="text" class="form-control" name="contract[number]" value="'.$this->number.'"></div>';
				$str .= '<div class="col-3"><input type="text" class="form-control calendar" name="contract[date]" value="'.$this->getDate($this->date).'"></div>';
				$str .= '<div class="col-3"><input type="text" class="form-control calendar" name="contract[date_crash]" '.$disabled.' value="'.$this->getDate($this->date_crash).'"></div>';
			$str .= '</div>';

									
			$str .= '<div class="input-group no-gutters">';
				$str .= '<div class="col-12">Срок поставки <a href="javascript://" id="adder_ship"><i class="icofont-plus-circle"></i></a></div>
					<div class="input-group no-gutters">';
				if ($this->shipdateAll) :
					foreach ($this->shipdateAll as $key => $value) :
						$item = '';
						if ($key == 0)
							$item = 'item';
						$str .= '<div class="col-3"><input 
							type="text" 
							class="form-control calendar '.$item.'" 
							name="contract[ship][]" 
							value="'.$this->getDate($value->date).'"></div>';
					endforeach;
				else :
					$str .= '<div class="col-3"><input type="text" class="form-control calendar item" name="contract[ship][]"></div>
						<div class="input-group no-gutters">';
				endif;
			$str .= '</div></div>';

			$str .= '<div class="input-group no-gutters py-1">';
				$str .= '<input type="checkbox" value="1" class="mr-1" name="contract[status]" '.$status.' > Взаиморасчёты проверены';
			$str .= '</div>';

			$str .= '<h3>Выдача:</h3>';
	    	$str .= '<div class="input-group no-gutters">
				<div class="col-3">Оформитель</div>
				<div class="col-3">Дата выдачи</div>
				<div class="col-3">Дата продажи</div>
				<div class="col-3">Дата списания</div>
			</div>';

			$str .= '<div class="input-group no-gutters">';
				$str .= '<div class="col-3"><select class="form-control" name="contract[close_author]">';
					$str .= '<option selected disabled>Выберите оформителя</option>';
					foreach (user::pluck('name', 'id') as $key => $value) :
						if($key==$this->close_author) 
							$check = 'selected'; 
						else 
							$check = '';
						$str .= "<option {$check} value='{$key}'>{$value}</option>";
					endforeach;
				$str .= '</select></div>';

				$str .= '<div class="col-3"><input type="text" 
						class="form-control calendar" 
						name="contract[close_date_issue]" 
						value="'.$this->getDate($this->close_date_issue).'"></div>';

				$str .= '<div class="col-3"><input type="text" 
						class="form-control calendar" 
						name="contract[close_date_sale]" 
						value="'.$this->getDate($this->close_date_sale).'"></div>';
						
				$str .= '<div class="col-3"><input type="text" 
						class="form-control calendar" 
						name="contract[close_date_offs]" 
						value="'.$this->getDate($this->close_date_offs).'"></div>';
			$str.='</div>';

		return $str;
    }
}
