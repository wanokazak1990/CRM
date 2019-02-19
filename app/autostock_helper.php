<?php 

namespace App;
use avacar;

Class autostock_helper {
	public $collection = array();
	public $response = array();
	public function __construct($data)
	{
		if(get_class($data) == 'Illuminate\Database\Eloquent\Collection');
			$this->collection = $data;
		if(get_class($data) == 'App\avacar');
			$this->collection = $data;
	}

	public function makeTableData($mas=array())
	{
		if(empty($this->collection)) 
			return array();
		
		elseif(count($this->collection)==1)
			$this->response[] = $this->dataForTable($this->collection);

		else
			foreach ($this->collection as $key=>$item) 
				$this->response[$key] = $this->dataForTable($item);
	}

	private function dataForTable($item,$array = array())
	{
        	$array['id'] = $item->id;
                $array['checkbox'] = '<input type="checkbox" value="'.$item->id.'" class="check-car" name="checkcar[]">';
                //Этап сделки
                $array['stage_deal'] =          $item->getStageDeal();
                //ПТС
                $array['status_pts'] =          $item->getStatusPTS();
                //Этап поставки
                $array['stage_delivery'] =      $item->getStageDelivery()['stage'];
                //Монитор состояния
                $array['monitor'] =             $item->getStageDelivery()['monitor'];
                //кнопка карт. машины
                $array['button_carlist'] = '<a href="#" class="opencar" car-id="'.$item->id.'"><i class="fas fa-car"></i></a>';
                //кнопка раблиста
                $array['button_worklist'] =     ($item->getWorklistId())?'<a href="#" class="car-worklist" worklist-id="'.$item->getWorklistId().'"><i class="fas fa-clipboard-list"></i></a>':''; 
                //Маркер логиста
                $array['logist_marker'] =       @$item->getLogistMarker->name;
                //Автор заказа
                $array['author'] =              @$item->getAuthor->name;
                //Дата заказа в производства
                $array['date_order'] =          $item->dateFormat('d.m.Y',@$item->getDateOrder->date);
                //Дата сборки в планируемая
                $array['date_planned'] =        $item->dateFormat('d.m.Y',@$item->getDatePlanned->date);
                //Дата уведомления о сборке
                $array['date_notification'] =   $item->dateFormat('d.m.Y',@$item->getDateNotification->date);
                //Дата сборки
                $array['date_build'] =          $item->dateFormat('d.m.Y',@$item->getDateBuild->date);
                //Дата готовности к отгрузке
                $array['date_ready'] =          $item->dateFormat('d.m.Y',@$item->getDateReady->date);
                //локация отгрузки
                $array['loc_ship'] =            @$item->model->country->getFlag().' '.@$item->model->country->city;
                //Дата отгрузки
                $array['date_ship'] =           $item->dateFormat('d.m.Y',@$item->getDateShip->date);
                //Дата приёмки на склад
                $array['date_storage'] =        $item->dateFormat('d.m.Y',@$item->date_storage);
                //техник
                $array['technic'] =             @$item->getTechnic->name;
                //Дата предпродажки
                $array['date_preparation'] =    $item->dateFormat('d.m.Y',$item->date_preparation);
                //радиокод
                $array['radio_code'] =           @$item->radio_code;
                //Обеспечение(пока только ид так как нет справочника)
                $array['provision'] =           @$item->provision;
                //Номер приходной накладной
                $array['receipt_number'] =        $item->receipt_number;
                //дата приходной накладной
                $array['receipt_date'] =        $item->dateFormat('d.m.Y',$item->receipt_date);
                //период отсрочки
                $array['period_otsrochka'] =    '';
                //Дата отсрочки
                $array['begin_otsrochka'] =     'begin_otsrochka';
                //расчётный закуп
                $array['raschet_purchase'] =    'raschet_zakup';
                //фактический закуп
                $array['actual_purchase'] =     $item->actual_purchase;
                //скидка при отгрузке
                $array['sum_discount'] =        'sum_discount';
                //дата оплаты птс
                $array['pts_datepay'] =         $item->dateFormat('d.m.Y',$item->pts_datepay);
                //Дата прихода птс
                $array['pts_datereception '] =  $item->dateFormat('d.m.Y',$item->pts_datereception);

                $array['year'] =                @$item->year;
                $array['brand'] =               ($item->brand)?$item->brand->getIcon():'';
                $array['model'] =               @$item->model->name;
                $array['complect_code']    =    @$item->complect->code;
                $array['complect'] =            @$item->complect->name.' '.@$item->complect->motor->getEasyName();
                $array['pack_code'] =           $item->stringPackName();
                $array['pack'] =                '<button type="button">PACKS</button>';
                $array['dops'] =                '<button type="button">DOPS</button>';
                $array['color_code'] =          $item->color->rn_code;
                $array['color_name'] =          $item->color->name;
                $array['vin'] =                 $item->vin;
                $array['order_number'] =        $item->order_number;
                $array['delivery_type'] =       ($item->DeliveryType)?$item->DeliveryType->name:'';
                $array['price_stock'] =         number_format($item->complect->price,0,'',' ').' руб.';
                $array['price_pack'] =          number_format($item->packPrice(),0,'',' ').' руб.';
                $array['price_dops_1'] =        number_format($item->dopprice,0,'',' ').' руб.';
                $array['price_dops_2'] =        number_format($item->dopprice,0,'',' ').' руб.';
                $array['price_discount'] =      'СКИДКА';
                $array['price_sell'] =          'Цена продажи';

                return $array;
        }
}