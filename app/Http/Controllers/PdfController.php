<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDF;

use App\crm_car_selection;
use App\avacar;
use App\ava_pack;
use App\crm_commercial_offer;
use App\crm_configurator;
use App\pack;

class PdfController extends Controller
{
    /**
     * Тестовое создание PDF
     */
    public function test()
    {
    	$data = [
			'header' => 'Привет!'
		];
		$pdf = PDF::loadView('pdf.test', $data);
		return $pdf->stream('test.pdf');
    }

    /**
     * Создание коммерческого предложения
     *
     * $worklist_id - идентификатор рабочего листа
     *
     * return - сгенерированный PDF файл с пользовательскими данными в новой вкладке браузера
     */
    public function createOffer(Request $request, $worklist_id)
    {
        $offer = new crm_commercial_offer();
        $offer->worklist_id = $worklist_id;
        $offer->creation_date = strtotime(date('d.m.Y H:i:s'));

        if ($request->has('cars_ids'))
        {
            $offer->cars_ids = implode(',', $request->cars_ids);
        }
        
        if ($request->has('cfg_cars'))
        {
            $offer->cfg_cars = implode(',', $request->cfg_cars);
        }

        $offer->save();

        return $this->generatePdf($offer->id);   	
    }

    /**
     * Открытие коммерческого предложения
     *
     * $request->offer_id - идентификатор коммерческого предложения в БД
     *
     * return - сгенерированный PDF файл с пользовательскими данными в новой вкладке браузера
     */
    public function openOffer(Request $request)
    {
        $offer = crm_commercial_offer::find($request->offer_id);

        if ($offer != null)
            return $this->generatePdf($offer->id);      
        else
            return $offer;
    }

    /**
     * Генерация PDF файла с коммерческим предложением
     *
     * $worklist_id - идентификатор рабочего листа
     * $cars_ids - массив идентификаторов существующих машин
     * $cfg_cars - массив идентификаторов сконфигурированных машин
     *
     * return - PDF файл с коммерческим предложением
     */
    public function generatePdf($offer_id)
    {
        $offer = crm_commercial_offer::find($offer_id);

        $cars_ids = $offer->cars_ids;
        $cfg_cars = $offer->cfg_cars;

        if ($cars_ids != null)
        {
            foreach (explode(',', $cars_ids) as $key => $car_id) 
            {
                $car = avacar::find($car_id);
                $cars[] = $this->getCarInfo($car);
            }
        }

        if ($cfg_cars != null)
        {
            foreach (explode(',', $cfg_cars) as $key => $cfg_id) 
            {
                $cars[] = $this->getCfgCarInfo($cfg_id);
            }
        }

        if ($cars_ids == null && $cfg_cars == null)
        {
            $car_id = crm_car_selection::where('worklist_id', $offer->worklist_id)->first()->car_id;
            $car = avacar::find($car_id);
            $cars[] = $this->getCarInfo($car);
        }

        $items = [
            'cars' => $cars
        ];

        $pdf = PDF::loadView('pdf.offer', $items);

        return $pdf->stream('offer.pdf');
    }

    /**
     * Генерация массива с инфой о машине
     *
     * $car - коллекция автомобиля, полученная ранее из бд по ид
     */
    public function getCarInfo($car = null)
    {
        if ($car != null)
        {
            $data['car_vin'] = $car->vin;
            $data['car_name'] = $car->brand->name.' '.$car->model->name;
            $data['complect_code'] = $car->complect->code;
            $data['complect_name'] = $car->complect->name;
            $data['complect_price'] = $car->complect->price;
            $data['img'] = '/storage/images/'.$car->model->link.'/'.$car->model->alpha;
            $data['color_code'] = $car->color->web_code;
            $data['color_rn_code'] = $car->color->rn_code;
            $data['color_name'] = $car->color->name;
            
            $data['car_info'][] = 'Двигатель '.$car->complect->motor->type->name." ".$car->complect->motor->valve.'-клапанный';
            $data['car_info'][] = 'Рабочий объем '.$car->complect->motor->size.'л. ('.$car->complect->motor->power.'л.с.)';
            $data['car_info'][] = 'КПП '.$car->complect->motor->transmission->name;
            $data['car_info'][] = 'Привод '.$car->complect->motor->wheel->name;

            if (count($car->dops) > 0)
            {
                foreach ($car->dops as $key => $value) 
                {
                    $data['dops'][] = $value->dop->name;
                }
            }
            else
            {
                $data['dops'][] = 'Доп. оборудование не установлено';
            }

            if ($car->dopprice == null)
                $data['car_dopprice'] = 0;
            else
                $data['car_dopprice'] = $car->dopprice;

            foreach ($car->complect->installoption as $key => $item)
            {
                $data['installed'][] = $item->option->name;
            }

            $packs = ava_pack::where('avacar_id', $car->id)->get();
            
            $data['fullprice'] = (int)$data['car_dopprice'] + (int)$data['complect_price'];

            $data['options'] = '';
            foreach ($packs as $key => $value) 
            {
                $data['options'] .= '<div>
                <div>'.$value->pack->name.' ('.$value->pack->code.') - '.$value->pack->price.'</div>';

                foreach ($value->pack->option as $k => $val) 
                {
                    $data['options'] .= '<div>'.$val->option->name.'</div>';
                }

                $data['options'] .= '</div>';

                $data['fullprice'] = $data['fullprice'] + (int)$value->pack->price;
            }

            return $data;
        }
        else
            return $car;
    }


    /**
     * Генерация массива с инфой о машине, созданной в Конфигураторе
     *
     * $cfg_id - ID сконфигурированной машины
     */
    public function getCfgCarInfo($cfg_id)
    {
        $cfg = crm_configurator::find($cfg_id);
        if ($cfg != null)
        {
            $data['car_vin'] = 'КОНФИГУРАТОР';
            $data['car_name'] = $cfg->model->brand->name.' '.$cfg->model->name;
            $data['complect_code'] = $cfg->complect->code;
            $data['complect_name'] = $cfg->complect->name;
            $data['complect_price'] = $cfg->complect->price;
            $data['img'] = '/storage/images/'.$cfg->model->link.'/'.$cfg->model->alpha;
            $data['color_code'] = $cfg->color->web_code;
            $data['color_rn_code'] = $cfg->color->rn_code;
            $data['color_name'] = $cfg->color->name;
            
            $data['car_info'][] = 'Двигатель '.$cfg->complect->motor->type->name." ".$cfg->complect->motor->valve.'-клапанный';
            $data['car_info'][] = 'Рабочий объем '.$cfg->complect->motor->size.'л. ('.$cfg->complect->motor->power.'л.с.)';
            $data['car_info'][] = 'КПП '.$cfg->complect->motor->transmission->name;
            $data['car_info'][] = 'Привод '.$cfg->complect->motor->wheel->name;

            foreach ($cfg->complect->installoption as $key => $item)
            {
                $data['installed'][] = $item->option->name;
            }
            
            $data['fullprice'] = (int)$data['complect_price'];

            $data['options'] = '';
            foreach (json_decode($cfg->options, true) as $key => $pack_id) 
            {
                $pack = pack::find($pack_id);

                $data['options'] .= '<div>
                <div>'.$pack->name.' ('.$pack->code.') - '.$pack->price.'</div>';

                foreach ($pack->option as $k => $val) 
                {
                    $data['options'] .= '<div>'.$val->option->name.'</div>';
                }

                $data['options'] .= '</div>';

                $data['fullprice'] = $data['fullprice'] + (int)$pack->price;
            }

            return $data;
        }
        else
            return $cfg;
    }

}
