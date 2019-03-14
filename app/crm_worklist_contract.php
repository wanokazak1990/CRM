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
    	return $this->hasMany('App\crm_contract_ship','contract_id','id');
    }
    public function shipdateLast()
    {
    	return $this->hasOne('App\crm_contract_ship','contract_id','id')->orderBy('id','DESC');
    }

    public function getDate($date)
    {
    	if(strlen($date)>6)
    		return date('d.m.Y',$date);
    }

    public function getHtml($crash=0,$status=0,$disabled='disabled')
    {

    	if($this->status) $status = 'checked';
    	if($this->crash)  {$crash  = 'checked';$disabled= '';}

    	$str = '<div class="container">';
	    	$str.='<h3>Контракт:</h3>';
	    	$str .= '<div class="input-group">
				<span class="col-3">Оформитель</span>
				<span class="col-3">Номер договора</span>
				<span class="col-3">Дата договора</span>
				<span class="col-3">
					Расторжение
					<input type="checkbox" name="contract[crash]" value="1" '.$crash.' >
				</span>
			</div>';

			$str .= '<div class="input-group">';
				$str.='<select class="form-control col-3" name="contract[author_id]">';
					$str.='<option selected disabled>Выберите автора</option>';
					foreach (user::pluck('name','id') as $key => $value) :
						if($key==$this->author_id) $check = 'selected'; 
						else $check ='';
						$str .= "<option {$check} value='{$key}'>{$value}</option>";
					endforeach;
				$str .='</select>';

				$str .='<input type="text" class="form-control col-3" name="contract[number]" value="'.$this->number.'">';
				$str .='<input type="text" class="form-control col-3 calendar" name="contract[date]" value="'.$this->getDate($this->date).'">';
				$str .='<input type="text" class="form-control col-3 calendar" name="contract[date_crash]" '.$disabled.' value="'.$this->getDate($this->date_crash).'">';
			$str.='</div>';

									
			$str .='<div class="input-group">';
				$str .='<span class="col-12">Срок поставки<a id="adder_ship"><i class="fa fa-plus"></i></a></span>';
				if($this->shipdateAll) :
					foreach ($this->shipdateAll as $key => $value) :
						$item = '';
						if($key==0)
							$item = 'item';
						$str .='<input 
							type="text" 
							class="form-control col-3 calendar '.$item.'" 
							name="contract[ship][]" 
							value="'.$this->getDate($value->date).'">';
					endforeach;
				else :
					$str .='<input type="text" class="form-control col-3 calendar item" name="contract[ship][]">';
				endif;
			$str .='</div>';

			$str.='<div class="input-group">';
				$str .= '<input type="checkbox" value="1" name="contract[status]" '.$status.' >Взаиморасчёты проверены';
			$str.='</div>';

			$str.='<h3>Выдача:</h3>';
	    	$str .= '<div class="input-group">
				<span class="col-3">Оформитель</span>
				<span class="col-3">Дата выдачи</span>
				<span class="col-3">Дата продажи</span>
				<span class="col-3">Дата списания</span>
			</div>';

			$str .= '<div class="input-group">';
				$str.='<select class="form-control col-3" name="contract[close_author]">';
					$str.='<option selected disabled>Выберите оформителя</option>';
					foreach (user::pluck('name','id') as $key => $value) :
						if($key==$this->close_author) $check = 'selected'; 
						else $check ='';
						$str .= "<option {$check} value='{$key}'>{$value}</option>";
					endforeach;
				$str .='</select>';

				$str .='<input type="text" 
						class="form-control col-3 calendar" 
						name="contract[close_date_issue]" 
						value="'.$this->getDate($this->close_date_issue).'">';
				$str .='<input type="text" 
						class="form-control col-3 calendar" 
						name="contract[close_date_sale]" 
						value="'.$this->getDate($this->close_date_sale).'">';
				$str .='<input type="text" 
						class="form-control col-3 calendar" 
						name="contract[close_date_offs]" 
						value="'.$this->getDate($this->close_date_offs).'">';
			$str.='</div>';
		$str.="</div>";

		return $str;
    }
}
