<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDF;

use App\crm_car_selection;
use App\avacar;
use App\ava_pack;

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
     * Создание PDF файла с коммерческим предложением
     * $id - идентификатор рабочего листа
     * return - PDF файл с пользовательскими данными в новой вкладке браузера
     */
    public function createOffer($id)
    {
    	$car_id = crm_car_selection::where('worklist_id', $id)->first()->car_id;

    	$car = avacar::find($car_id);

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

        $packs = ava_pack::where('avacar_id', $car_id)->get();
        
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

    	$items = [
			'wl_id' => $id,
			'item' => $data
		];

		$pdf = PDF::loadView('pdf.offer', $items);
		return $pdf->stream('offer.pdf');
    }
}
