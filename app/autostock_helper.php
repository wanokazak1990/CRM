<?php 

namespace App;
use avacar;
use App\crm_offered_dop;
use App\crm_worklist_company;

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
		
		/*elseif(count($this->collection)==1)
			$this->response[] = $this->dataForTable($this->collection);*/

		else
			foreach ($this->collection as $key=>$item) 
				$this->response[$key] = $this->dataForTable($item);
	}

	private function dataForTable($item,$array = array())
	{
                //init
                $client=0;
                $companySale=0;
                $offeredDops=0;
                $dopsSale=0;
                $pay=0;
                //id worklist
                $worklist_id = $item->getWorklistId();
                if($worklist_id)
                {
                        //хранит инфо о клиенте что бы по сто раз не вызывать
                        $client = $item->getClient();
                        //сумма скидки
                        $companySale = crm_worklist_company::where('wl_id',$worklist_id)->where('razdel',1)->sum('sum');
                        //предлженное оборудование
                        $offeredDops = crm_offered_dop::where('worklist_id',$worklist_id)->first();
                        //скидка на попы
                        $dopsSale = crm_worklist_company::where('wl_id',$worklist_id)->where('razdel',2)->sum('sum');
                        //платежи
                        $pay = \App\crm_client_pay::where('worklist_id',$worklist_id)->where('status',1)->get();
                }

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
                $array['button_carlist'] = '<a href="javascript://" class="opencar" car-id="'.$item->id.'"><i class="fas fa-car"></i></a>';
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
                $array['provision'] =             @$item->getProvision->name;
                //Номер приходной накладной
                $array['receipt_number'] =        $item->receipt_number;
                //дата приходной накладной
                $array['receipt_date'] =        $item->dateFormat('d.m.Y',$item->receipt_date);
                //период отсрочки
                $array['period_otsrochka'] =    @$item->getDateProvisionLast->count_days;
                //Дата отсрочки
                $array['begin_otsrochka'] =     $item->dateFormat('d.m.Y',@$item->getDateProvisionLast->date_provision);
                //расчётный закуп
                $array['raschet_purchase'] =    number_format($item->estimated_purchase,0,'',' ').' р.';
                //фактический закуп
                $array['actual_purchase'] =     number_format($item->actual_purchase,0,'',' ').' р.';
                //скидка при отгрузке
                $array['sum_discount'] =        number_format($item->shipping_discount,0,'',' ').' р.';
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
                $array['pack'] =                '<button class="stock-button" type="button" mind="pack" car-id="'.$item->id.'">PACKS</button>';
                $array['dops'] =                '<button class="stock-button" type="button" mind="dop" car-id="'.$item->id.'">DOPS</button>';
                $array['color_code'] =          $item->color->rn_code;
                $array['color_name'] =          '<span>'.$item->color->getColorIcon().'</span>'.$item->color->name;
                $array['vin'] =                 $item->vin;
                $array['order_number'] =        $item->order_number;
                $array['delivery_type'] =       ($item->DeliveryType)?$item->DeliveryType->name:'';

                $array['price_stock'] =         number_format($item->complect->price,0,'',' ').' р.';

                $array['price_pack'] =          number_format($item->packPrice(),0,'',' ').' р.';

                $array['sale'] =                number_format($companySale,0,'',' ').' р.';

                $array['price_out_dops'] =      
                        number_format(
                                $item->packPrice()+
                                $item->complect->price-
                                $companySale,0,'',' '
                        ).' р.';

                $array['dop_istall'] =          number_format($item->dopprice,0,'',' ').' р.';

                $array['dop_offered'] =         ($offeredDops)?number_format($offeredDops->price,0,'',' ').' р.':'0 р.';

                $array['dop_sale'] =            number_format($dopsSale,0,'',' ').' р.';

                $array['dop_total_price'] = number_format(
                                $item->dopprice+
                                @$offeredDops->price-
                                $dopsSale
                ,0,'',' ').' р.';  

                $array['total'] = number_format(preg_replace("/[^,.0-9]/", '', $array['price_out_dops'])+preg_replace("/[^,.0-9]/", '', $array['dop_total_price']),0,'',' ').' р.';   

                //кнопка раблиста
                $array['button_worklist'] =     ($worklist_id)?'<a href="javascript://" class="car-worklist" worklist-id="'.$worklist_id.'"><i class="fas fa-clipboard-list"></i></a>':''; 

                ///////////////////
                /*ОПЛАТА КЛИЕНТОМ*/
                $array['client_pay_sum']        = ($pay)?number_format(@$pay->sum('pay'),0,'',' ').' р.':'0 р.';
                $array['client_pay_date']       = ($pay)?date('d.m.Y', @$pay->last()->date):'';
                $array['client_pay_residue']    = ($pay)?number_format(@$pay->sum('debt'),0,'',' ').' р.':'0 р.';

                /************************************************/
                /*      ПЕРСОНАЛЬНЫЕ ДАННЫЕ КЛИЕНТА НАЧАЛО      */
                /************************************************/
                
                //тип клиента (физик,юрик)
                $array['type_contact']  = isset($client->type)?$client->type->name:'';
                //имя клиента
                $array['client_name']   = @$client->lastname.' '.@$client->name.' '.@$client->secondname;
                //телефон клиента
                $array['client_phone']  = isset($client->contact->phone)?$client->contact->phone:'';
                //почта клиента
                $array['client_mail']   = isset($client->contact->email)?$client->contact->email:'';
                //тип контакта
                $array['client_marker'] = isset($client->contact->getMarker)?$client->contact->getMarker->name:'';
                //адрес клиента
                $array['client_address'] = @$client->address;
                //день рождения клиента
                $array['client_dirthday'] = $item->dateFormat('d.m.Y',@$client->birthday);
                /************************************************/
                /*      ПЕРСОНАЛЬНЫЕ ДАННЫЕ КЛИЕНТА КОНЕЦ       */
                /************************************************/

                return $array;
        }
}