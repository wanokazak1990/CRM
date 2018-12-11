<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\oa_brand;
class oa_color extends Model
{
    //
    protected $fillable = ['name','rn_code','web_code','brand_id'];
    public $timestamps = false;

    public function brand()
    {
    	$res = $this->belongsTo('App\oa_brand');
    	if($res)
    		return $res;
    }

    public function getColorIcon()
    {
    	$mas = explode(',', $this->web_code);
    	if(count($mas)>1)
    		return '<div style="
    					border:1px solid #ccc; 
    					width: 30px; 
    					height: 30px; 
    					background: linear-gradient('.$mas[0].' 50%,  '.$mas[1].' 50%)">
				</div>';
		else 
			return '<div style="
    					border:1px solid #ccc; 
    					width: 30px; 
    					height: 30px; 
    					background: '.$this->web_code.'">
				</div>';		
    }
}
