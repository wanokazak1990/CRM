<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class avacar extends Model
{
    //
    public $timestamps = true;
    protected $fillable = ['delivery_type', 'creator_id', 'logist_marker','logist_date', 'radio_code', 'order_number', 'swap', 'brand_id', 'model_id', 'complect_id', 'color_id', 'vin', 'location_id', 'status_id', 'year', 'dopprice', 'created_at', 'updated_at', 'prodaction', 'date_storage', 'date_preparation', 'receipt_number', 'receipt_date', 'technic', 'estimated_purchase', 'actual_purchase', 'shipping_discount', 'pts_datepay', 'pts_datereception', 'debited_date', 'commercial application','provision','detail_discount'];
    
    /*protected $attributes = [
    	'dopprice' => 0,
    	'prodaction'=> 0
    ];*/

    public function DeliveryType()
    {
        return $this->hasOne('App\crm_delivery_type','id','delivery_type');
    }

    public function getYearArray()
    {
    	$mas[date('Y')-1] = date('Y')-1;
    	$mas[date('Y')-0] = date('Y')-0;
    	$mas[date('Y')+1] = date('Y')+1;
    	return $mas;
    }

    public function brand()
    {
    	return $this->hasOne('App\oa_brand','id','brand_id');
    }

    public function model()
    {
    	return $this->hasOne('App\oa_model','id','model_id');
    }

    public function complect()
    {
    	return $this->hasOne('App\oa_complect','id','complect_id');
    }

    public function status()
    {
    	return $this->hasOne('App\ava_status','id','status_id');
    }

    public function location()
    {
    	return $this->hasOne('App\ava_loc','id','location_id');
    }

    public function packs()
    {
    	return $this->hasMany('App\ava_pack','avacar_id','id');
    }

    public function stringPackName($array = array(), $str = '')
    {
        $packs = $this->packs;
        if($packs)
        {
            foreach ($packs as $key => $item) {
                if(isset($item->pack))
                    $array[] = $item->pack->code;
            }
            $str = implode($array,', ');
        }
        return $str;
    }

    public function dops()
    {
    	return $this->hasMany('App\ava_dop','avacar_id','id');
    }

    public function packPrice($price=0)
    {
    	if(isset($this->packs))
    	{
    		foreach ($this->packs as $key => $pack) 
    		{	
    			if(isset($pack->pack))
    			  	$price += $pack->pack->price;
    		}
    	}
    	return $price;
    }				
    public function totalPrice($price = 0)
    {
    	$price += $this->dopprice;
    	if(isset($this->complect))
    		$price+=$this->complect->price;
    	$price+=$this->packPrice();
    	return $price;
    }

    public function color()
    {
        return $this->hasOne('App\oa_color','id','color_id');
    }

    public function getAuthor()
    {
        return $this->hasOne('App\user','id','creator_id');
    }

    public function getDateOrder()
    {
        return $this->hasOne('App\ava_date_order','car_id','id');
    }

    //ПЛАНИРУЕМАЯ ДАТА СБОРКИ В ОДНОМ ЭКЗЕМПЛЯРЕ
    public function getDatePlanned()
    {
        return $this->hasOne('App\ava_date_planned','car_id','id')->orderBy('id','DESC');
    }
    //ПЛАНИРУЕМАЯ ДАТА СБОРКИ ВСЁ
    public function getDatePlannedAll()
    {
        return $this->hasMany('App\ava_date_planned','car_id','id');
    }

    public function getDateBuild()
    {
        return $this->hasOne('App\ava_date_build','car_id','id');
    }

    public function getDateReady()
    {
        return $this->hasOne('App\ava_date_ready','car_id','id');
    }



    public function getDateShip()
    {
        return $this->hasOne('App\ava_date_ship','car_id','id')->orderBy('id','DESC');
    }
    public function getDateShipAll()
    {
        return $this->hasMany('App\ava_date_ship','car_id','id');
    }



    public function getDateNotification()
    {
        return $this->hasOne('App\ava_date_notification','car_id','id');
    }

    public function getTechnic()
    {
        return $this->hasOne('App\user','id','technic');
    }

    public function getLogistMarker()
    {
        return $this->hasOne('App\crm_logist_marker','id','logist_marker');
    }

    public function carSelection()
    {
        return $this->hasOne('App\crm_car_selection','car_id','id');
    }

    public function getDateProvision()
    {
        return $this->hasMany('App\ava_data_provision','car_id','id');
    }
    public function getDateProvisionLast()
    {
        return $this->hasOne('App\ava_data_provision','car_id','id')->orderBy('id','DESC');
    }

    public function getProvision()
    {
        return $this->hasOne('App\crm_list_provision','id','provision');
    }

    public function getDiscount()
    {
        return $this->hasMany('App\ava_discount','car_id','id');
    }
    public function sumDiscount($sum=0)
    {
        if(!empty($this->getDiscount))
            foreach ($this->getDiscount as $key => $value) {
                $sum+=$value->sale;
            }
        return $sum;
    }


    public function getWorklistId()
    {
        $res = $this->carSelection;
        if($res)
            return $res->worklist_id;
        else
            return '';
    }

    public function getClient()
    {
        $worklist = new crm_client();
        $res = $this->carSelection;
        if($res)
        {
            $worklist = crm_client::with('contact')->where('id',$res->client_id)->first();
        }
        return $worklist;
    }

    public function getDay($date,$str = '')
    {
        $ret = '';
        $current = date('d.m.Y');
        $diff = strtotime($current)-$date;
        $diff = $diff/24/60/60;
        
        $ret = $diff;
        if($diff<0)
            $ret = $str.' '.abs($diff);

        if(abs($diff)%10==1)
            $day = 'день';
        else if(abs($diff)%10>=2 && abs($diff)%10<=4 && !in_array(abs($diff), [11,12,13,14])) 
            $day = 'дня';
        else
            $day = 'дней';

        return $ret.' '.$day;
    }

    public function getStageDelivery($str = '')
    {
        if(@$this->getAuthor->role = 2)
            $str = ['stage'=>'Заявка','monitor'=>''];

        if(@$this->getAuthor->role = 1)
            $str = ['stage'=>'Заявка','monitor'=>$this->getDay(strtotime($this->created_at->format('d.m.Y')))];

        if(@$this->getDateOrder->date){
            $str = ['stage'=>'На валидации','monitor'=>$this->getDay($this->getDateOrder->date,'через')];
        }

        if(@$this->getDatePlanned->date)
        {
            $current = strtotime(date('d.m.Y'));

            if($current<@$this->getDatePlanned->date){
                $stage  = 'В производстве';
                $status = date('d.m.Y',@$this->getDatePlanned->date);
            }
            else {
                $stage = 'В производстве';
                $status = 'Идёт сборка';
            }

            $str = ['stage'=>$stage,'monitor'=>$status];
        }

        if(@$this->getDateBuild->date)
            $str = ['stage'=>'На складе завода','monitor'=>@$this->model->country->city];

        if(@$this->getDateReady->date)
            $str = ['stage'=>'На складе отгрузки','monitor'=>@$this->model->country->storage];

        if(@$this->getDateShip->date)
            $str = ['stage'=>'Доставка дилеру','monitor'=>$this->getDay($this->getDateShip->date,'начнётся через')];

        if(@$this->date_storage)
            $str = ['stage'=>'Приёмка дилером','monitor'=>$this->getDay($this->date_storage,'через')];

        if(@$this->receipt_date)
            $str = ['stage'=>'На складе дилера','monitor'=>$this->getDay($this->receipt_date,'через')];

        if(@$this->exit)
            $str = ['stage'=>'Выдан','monitor'=>''];

        return $str;
    }

    //доделать этап сделки
    public function getStageDeal()
    {
        return 'Свободный';
    }

    public function getStatusPTS()
    {
        if($this->pts_datereception)
            return '<b class="green"></b>';;
        if($this->pts_datepay)
            return '<b class="yellow"></b>';
    }

    public function dateFormat($format,$date)
    {
        if(!$date)
            return '';
        return date($format,$date);
    }

}
