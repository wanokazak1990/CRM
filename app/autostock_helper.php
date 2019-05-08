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
                $contract = 0;
                $kredit = 0;
                $kreditProduct = array();
                $marginService = 0;
                $worklist = '';
                $traffic = 0;
                $needcars = 0;
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
                        //договор
                        $contract = crm_worklist_contract::with('getAuthor')->with('getCloser')->with('shipdateLast')->where('worklist_id',$worklist_id)->first();
                        //кредит
                        $kredit = crm_worklist_kredit::with('getAktualWaiting')->where('worklist_id',$worklist_id)->first();

                        $kreditProduct=kredit_product::pluck('name','id');

                        $marginService = number_format(\App\crm_ofu_product::sumProfit($worklist_id),0,'',' ');

                        $worklist = crm_worklist::with('traffic')->find($worklist_id);

                        $needcars = crm_need_car::where('worklist_id',$worklist_id)->first();

                }

        	$array['id'] = $item->id;
                $array['checkbox'] = '<input type="checkbox" value="'.$item->id.'" class="check-car" name="checkcar[]">';
                //ПТС
                $array['status_pts'] =          $item->getStatusPTS();
                //Этап сделки
                $array['stage_deal'] =          $item->getStageDeal();                
                //Этап поставки
                $array['stage_delivery'] =      $item->getStageDelivery()['stage'];
                //Монитор состояния
                $array['monitor'] =             $item->getStageDelivery()['monitor'];
                //кнопка карт. машины
                $array['button_carlist'] = '<a href="javascript://" class="opencar" car-id="'.$item->id.'"><i class="icofont-auto-mobile" style="font-size: 20px;"></i></a>';
                //Маркер логиста
                $array['logist_marker'] =       @$item->getLogistMarker->name;
                //Автор заказа
                $array['author'] =              @$item->getAuthor->name;
                //Дата заказа в производства
                $array['date_order'] =          $item->dateFormat('d.m.Y',@$item->getDateOrder->date);
                //мануфактура
                $array['manufacture'] =         @$item->model->country->getFlag().' '.@$item->model->country->city;
                //Дата сборки в планируемая
                $array['date_planned'] =        $item->dateFormat('d.m.Y',@$item->getDatePlanned->date);
                //Дата уведомления о сборке
                $array['date_notification'] =   $item->dateFormat('d.m.Y',@$item->getDateNotification->date);
                //Дата сборки
                $array['date_build'] =          $item->dateFormat('d.m.Y',@$item->getDateBuild->date);
                //Дата готовности к отгрузке
                $array['date_ready'] =          $item->dateFormat('d.m.Y',@$item->getDateReady->date);
                //локация отгрузки
                $array['loc_ship'] =            @$item->model->country->storage;
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
                
                $array['pack'] = ($item->stringPackName())?               
                        '<a href="javascript://" class="stock-button" data-toggle="modal" data-target="#optionsModal" mind="pack" car-id="'.$item->id.'">
                                <i class="icofont-tools-bag" style="font-size: 20px;"></i>
                        </a>'
                        :'';

                $array['dops'] = ($item->dopprice || $offeredDops)?               
                        '<a href="javascript://" class="stock-button" data-toggle="modal" data-target="#optionsModal" mind="dop" car-id="'.$item->id.'">
                                <i class="icofont-cart" style="font-size: 20px;"></i>
                        </a>'
                        :'';
                
                //CAR COLOR
                $iconCol = '';
                if(isset($item->color))
                        $iconCol = $item->color->getColorIcon();

                $array['color_code'] =          @$item->color->rn_code;
                $array['color_icon'] =          '<span>'.$iconCol.'</span>';
                $array['color_name'] =          @$item->color->name;
                
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
                $array['button_worklist'] =     ($worklist_id)?'<a href="javascript://" class="car-worklist" worklist-id="'.$worklist_id.'"><i class="icofont-ui-file" style="font-size: 20px;"></i></a>':''; 

                ///////////////////
                /*ОПЛАТА КЛИЕНТОМ*/
                $array['client_pay_sum']        = ($pay)?number_format(@$pay->sum('pay'),0,'',' ').' р.':'0 р.';
                $array['client_pay_date']       = ($pay)?date('d.m.Y', @$pay->last()->date):'';
                $array['client_pay_residue']    = ($pay)?number_format(@$pay->last()->debt,0,'',' ').' р.':'0 р.';

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
                
                $array['type_pay'] = ($needcars)?$needcars->getPayTypeName():'';
                $array['pay_trade'] = '';
                $array['old_manager'] = isset($worklist->oldManager)?$worklist->oldManager->name:'';
                $array['manager'] = isset($worklist->manager)?$worklist->manager->name:'';
                $array['assigned_action'] = '';
                $array['assigned_date'] = '';
                $array['pay_margin'] = '';
                $array['client_request'] = '';

                //ДОГОВОР
                $array['contract_author'] =             isset($contract->getAuthor)?$contract->getAuthor->name:'';
                $array['contract_number'] =             ($contract)?$contract->number:'';
                $array['contract_date'] =               isset($contract->date)?date('d.m.Y',$contract->date):'';
                $array['contract_ship'] =               isset($contract->shipdateLast)?date('d.m.Y',$contract->shipdateLast->date):'';
                $array['contract_datecrash'] =          isset($contract->date_crash)?date('d.m.Y',$contract->date_crash):'';
                $array['contract_closeauthor'] =        isset($contract->getCloser)?$contract->getCloser->name:'';
                $array['contract_close_date_issue'] =   isset($contract->close_date_issue)?date('d.m.Y',$contract->close_date_issue):'';
                $array['contract_close_date_sale'] =    isset($contract->close_date_sale)?date('d.m.Y',$contract->close_date_sale):'';
                $array['contract_close_date_offs'] =    isset($contract->close_date_offs)?date('d.m.Y',$contract->close_date_offs):'';

                //КРЕДИТ
                $array['kredit_bank'] = isset($kredit->getAktualWaiting)        ?$kredit->getAktualWaiting->kreditor->name:'';

                $array['kredit_author'] = isset($kredit->getAktualWaiting)      ?$kredit->getAktualWaiting->author->name:'';

                $array['kredit_date_in'] = (isset($kredit->getAktualWaiting) && $kredit->getAktualWaiting->date_in)     ?date('d.m.Y',$kredit->getAktualWaiting->date_in):'';

                $array['kredit_status'] = isset($kredit->getAktualWaiting)      ?$kredit->getAktualWaiting->status():'';

                $array['kredit_status_date'] = (isset($kredit->getAktualWaiting) && $kredit->getAktualWaiting->status_date) ?date('d.m.Y',$kredit->getAktualWaiting->status_date):'';

                $array['kredit_date_action'] = (isset($kredit->getAktualWaiting) && $kredit->getAktualWaiting->date_action) ?date('d.m.Y',$kredit->getAktualWaiting->date_action):'';

                $array['kredit_payment'] = isset($kredit->getAktualWaiting)     ?number_format($kredit->getAktualWaiting->payment,0,'',' '):'';

                $array['kredit_sum'] = isset($kredit->getAktualWaiting)         ?number_format($kredit->getAktualWaiting->sum,0,'',' '):'';

                $array['kredit_date_offer'] = (isset($kredit->getAktualWaiting) && $kredit->getAktualWaiting->date_offer)  ?date('d.m.Y',$kredit->getAktualWaiting->date_offer):'';

                $array['kredit_valid'] = (isset($kredit->valid_date) && $kredit->valid_date!=0)             ?date('d.m.Y',$kredit->valid_date):'';

                if(isset($kredit->getAktualWaiting))
                        $installProduct = explode('|', $kredit->getAktualWaiting->product);
                else
                        $installProduct = [];
                $array['kredit_prod1'] = (in_array(1, $installProduct))?$kreditProduct[1]:'';
                $array['kredit_prod2'] = (in_array(2, $installProduct))?$kreditProduct[2]:'';
                $array['kredit_prod3'] = (in_array(3, $installProduct))?$kreditProduct[3]:'';

                $array['margin_kredit'] =       isset($kredit->margin_kredit)   ?number_format($kredit->margin_kredit,0,'',' '):'';
                $array['margin_prod'] =         isset($kredit->margin_product)  ?number_format($kredit->margin_product,0,'',' '):'';
                $array['margin_other'] =        isset($kredit->margin_other)    ?number_format($kredit->margin_other,0,'',' '):'';
                $array['margin_total'] =        isset($kredit->margin_other)    ?number_format($kredit->totalMargin(),0,'',' '):'';
                $array['margin_service'] =      $marginService;

                $array['worklist_id'] = $worklist_id;
                $array['worklist_date'] = ($worklist)?$worklist->created_at->format('d.m.Y'):'';
                $array['traffic_type'] = isset($worklist->traffic->traffic_type)?$worklist->traffic->traffic_type->name:'';
                $array['traffic_model'] = isset($worklist->traffic->model)?$worklist->traffic->model->name:'';
                return $array;
        }
}