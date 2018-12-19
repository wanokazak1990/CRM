<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\company;
use App\oa_dop;
use App\oa_model;
use App\type_transmission;
use App\type_wheel;
use App\ava_loc;
use App\company_data;
use App\company_dop;

use PhpOffice\PhpWord\PhpWord as Word;

class CompanyController extends Controller
{
    //
	protected $rules = [
    		
    		'day_in'=>'required',
    		'day_out'=>'required',
    		'name'=>'required|min:5',
    		'title'=>'required',
    		'text'=>'required',
    		'ofer'=>'required',
    		'status'=>'required',
    		'timer'=>'required',
    		'razdel'=>'required',
    		'scenario'=>'required',
    		'main'=>'required',
    		'immortal'=>'required'

    ];

    public function export()
    {
        $list = company::with('exception')->get();
        $phpWord = new Word();
        $section = $phpWord->addSection();
        foreach ($list as $key => $company) 
        {
            $fontStyle = array('name' => 'Times New Roman', 'size' => 16,'color' => '333','italic'=>false);
            $section->addText($company->name,$fontStyle);
            //date
            $section->addText('Действует: с '.date('d.m.Y',$company->day_in).' до '.date('d.m.Y',$company->day_out));
            
            //action
            $action = $company->getScenario()[$company->scenario];                
                if($company->scenario==1) :
                    $action.=$company->getRazdels()[$company->razdel].
                            ': '.$company->value.'% (огранич.'.number_format($company->max,0,'',' ').' руб.) на ';
                    if($company->base)
                        $action.=' база '; 
                    if($company->option)
                        $action.=' опции '; 
                    if($company->dops)
                        $action.=' допы ' ;
                endif;

                if($company->scenario==2)
                    $action.=$company->getRazdels()[$company->razdel].': '.number_format($company->bydget,0,'',' ').' руб.';
                
                if($company->scenario==3)
                    $action.=$company->getRazdels()[$company->razdel].': номенклатура';
                
                if($company->scenario==4)
                    $action.=$company->getRazdels()[$company->razdel].': '.$company->ofer;
               
            $section->addText($action);

            //exception
            $type = '';
            foreach($company->exception as $exception) :
                if($exception->type != $type)
                {
                    $type = $exception->type;
                    if($type==1)
                        $section->addText('Включения');
                    else
                        $section->addText('Исключения');
                }
                $action = '- ';
                if(!empty($exception->vin))
                    $action.= $exception->vin;
                
                if(isset($exception->getCurrentModel))
                    $action.= $exception->getCurrentModel->name;
               
                if(isset($exception->getCurrentComplect))
                    $action.= $exception->getComplectName();
                
                if(isset($exception->getCurrentTransmission))
                    $action.= $exception->getCurrentTransmission->name;
               
                if(isset($exception->getCurrentWheel))
                    $action.= $exception->getCurrentWheel->name;
              
                if(isset($exception->getCurrentLocation))
                    $action.= $exception->getCurrentLocation->name;
              
                if(!empty($exception->pricestart))
                    $action.= 'цена от: '.number_format($exception->pricestart,0,'',' ').' руб.';
                
                if(!empty($exception->pricefinish))
                    $action.= 'цена до: '.number_format($exception->pricefinish,0,'',' ').' руб.';
            
                $section->addText($action);
            endforeach;
            $section->addText('');
        }
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('export_company.docx'));
        } catch (Exception $e) {

        }
        return response()->download(storage_path('export_company.docx'));
    }


    public function list(Request $request)
    {
        if($request->has('export'))
            return redirect()->route('companyexport');

        $list = company::with('exception')->get();
    	return view('company.list')
    		->with('list',$list)//список брендов
			->with('title','Список акций')//заголовок
			->with(['addTitle'=>'Новая акция','route'=>'companyadd'])
			->with('edit','companyedit')
			->with('delete','companydelete');
    }

    public function add()
    {
    	$company = new company();
    	$dops = oa_dop::pluck('name','id');
    	$models = oa_model::pluck('name','id');
    	$transmissions = type_transmission::pluck('name','id');
    	$wheels = type_wheel::pluck('name','id');
    	$locations = ava_loc::pluck('name','id');
    	return view('company.add')
    		->with('company',$company)//список брендов
			->with('title','Новая акция')
			->with('models',$models)
			->with('transmissions',$transmissions)
			->with('wheels',$wheels)
			->with('locations',$locations)
			->with('dops',$dops);
    }

    public function put(Request $request)
    {	
    	$this->validate(
    		$request,$this->rules
    	);
    	if($request->has('submit')) ://если нажата создать
    		$company = new company($request->input());//вставляем в модель компании данные из формы
    		$company->day_in = strtotime($company->day_in);
    		$company->day_out = strtotime($company->day_out);
    		$company->save();//сохраняем компанию
    		if($request->has('dops')) ://если выбраны допы для номенклатуры
    			foreach ($request->input('dops') as $key => $id) ://то прохожу по им всем
    				$company_dop = new company_dop([//создаю модель номенклатуры компании
    					'company_id'=>$company->id,//записываю ид компании
    					'dop_id'=>$id//записываю ид допа
    				]);
    				$company_dop->save();//сохраняю
    			endforeach;
    		endif;

    		foreach ($request->type as $key => $value) ://проходимся по включениям/исключениям (проверять на существование не обязательно так как 2 поле априори всегда будут, хоть и пустые)
    			$mas = array();//создаю пустой массив для хранения исключений/включений
    			$mas['type'] = $value;//записываю тип (включение-1 или исключение-0 )
    			if(isset($request->vin[$key]))//если есть вин с индексом кей
    				$mas['vin'] = $request->vin[$key];//заносим его в массив

    			if(isset($request->model_id[$key]))
    				$mas['model_id'] = $request->model_id[$key];

    			if(isset($request->complect_id[$key]))
    				$mas['complect_id'] = $request->complect_id[$key];

    			if(isset($request->transmission_id[$key]))
    				$mas['transmission_id'] = $request->transmission_id[$key];

    			if(isset($request->wheel_id[$key]))
    				$mas['wheel_id'] = $request->wheel_id[$key];

    			if(isset($request->location_id[$key]))
    				$mas['location_id'] = $request->location_id[$key];

    			if(isset($request->pricestart[$key]))
    				$mas['pricestart'] = $request->pricestart[$key];

    			if(isset($request->pricefinish[$key]))
    				$mas['pricefinish'] = $request->pricefinish[$key];
    			$company_data = new company_data($mas);//создаём модель исключений/включений из массива мас
    			$company_data->company_id = $company->id;//подзаписываю id компании в поле компани_ид
    			if($company_data->checkEmpty())//проверяю на пустоту (тайп и компани_ид игнорируются), если не пуста 
    				$company_data->save();//сохраняю
    			unset($mas,$company_data);//чищю масив и модель иключений/включений
    		endforeach;
    	endif;
    	return redirect()->route('companylist');
    }

    public function edit($id)
    {
    	$company = company::with('exception','dops')->find($id);
    	$dops = oa_dop::pluck('name','id');
    	$models = oa_model::pluck('name','id');
    	$transmissions = type_transmission::pluck('name','id');
    	$wheels = type_wheel::pluck('name','id');
    	$locations = ava_loc::pluck('name','id');
    	return view('company.add')
    		->with('company',$company)//список брендов
			->with('title','Новая акция')
			->with('models',$models)
			->with('transmissions',$transmissions)
			->with('wheels',$wheels)
			->with('locations',$locations)
			->with('dops',$dops);
    }

    public function update(Request $request, $id)
    {
    	$this->validate(
    		$request,$this->rules
    	);
    	if($request->has('submit')) ://если нажата создать
    		$company = company::with('exception','dops')->find($id);//вставляем в модель компании данные из формы
            $company->base = 0;
            $company->dop = 0;
            $company->option = 0;
    		$mas = $request->input();
    		$mas['day_in'] = strtotime($mas['day_in']);
    		$mas['day_out'] = strtotime($mas['day_out']);
    		$company->update($mas);//сохраняем компанию

    		company_dop::where('company_id',$company->id)->delete();
    		if($request->has('dops')) ://если выбраны допы для номенклатуры
    			foreach ($request->input('dops') as $key => $id) ://то прохожу по им всем
    				$company_dop = new company_dop([//создаю модель номенклатуры компании
    					'company_id'=>$company->id,//записываю ид компании
    					'dop_id'=>$id//записываю ид допа
    				]);
    				$company_dop->save();//сохраняю
    			endforeach;
    		endif;

    		company_data::where('company_id',$company->id)->delete();
    		foreach ($request->type as $key => $value) ://проходимся по включениям/исключениям (проверять на существование не обязательно так как 2 поле априори всегда будут, хоть и пустые)
    			$mas = array();//создаю пустой массив для хранения исключений/включений
    			$mas['type'] = $value;//записываю тип (включение-1 или исключение-0 )
    			if(isset($request->vin[$key]))//если есть вин с индексом кей
    				$mas['vin'] = $request->vin[$key];//заносим его в массив

    			if(isset($request->model_id[$key]))
    				$mas['model_id'] = $request->model_id[$key];

    			if(isset($request->complect_id[$key]))
    				$mas['complect_id'] = $request->complect_id[$key];

    			if(isset($request->transmission_id[$key]))
    				$mas['transmission_id'] = $request->transmission_id[$key];

    			if(isset($request->wheel_id[$key]))
    				$mas['wheel_id'] = $request->wheel_id[$key];

    			if(isset($request->location_id[$key]))
    				$mas['location_id'] = $request->location_id[$key];

    			if(isset($request->pricestart[$key]))
    				$mas['pricestart'] = $request->pricestart[$key];

    			if(isset($request->pricefinish[$key]))
    				$mas['pricefinish'] = $request->pricefinish[$key];
    			$company_data = new company_data($mas);//создаём модель исключений/включений из массива мас
    			$company_data->company_id = $company->id;//подзаписываю id компании в поле компани_ид
    			if($company_data->checkEmpty())//проверяю на пустоту (тайп и компани_ид игнорируются), если не пуста 
    				$company_data->save();//сохраняю
    			unset($mas,$company_data);//чищю масив и модель иключений/включений
    		endforeach;

    	endif;
    	return redirect()->route('companylist');
    }

    public function delete($id)
    {
    	$company = company::find($id);
        return view('company.del')
            ->with('title','Удаление компании')
            ->with('company',$company);
    }

    public function destroy(Request $request,$id)
    {
    	if($request->has('delete'))
        {
            company::destroy($id);
            company_data::where('company_id',$id)->delete();
            company_dop::where('company_id',$id)->delete();
        }
        return redirect()->route('companylist');
    }
}
