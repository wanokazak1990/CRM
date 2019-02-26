<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\crm_worklist_company;

class company extends Model
{
    //
    protected $fillable = [
        'day_in','day_out','status','timer','razdel','name','scenario',
        'base','option','dop','value','max','valute','bydget','file',
        'title','text','ofer','main','immortal','reparation', 'reparation_type', 
        'reparation_base', 'reparation_pack', 'reparation_dops', 'reparation_value', 'reparation_bydget'
    ];

    public static function getActualCompany()
    {
        $res = company::with('positive')->with('negative')->with('dops')
            ->where('status','1')
            ->where('day_in','<=',strtotime(date('d.m.Y')))
            ->where('day_out','>=',strtotime(date('d.m.Y')))
            ->get();
        return $res;
    }

    /*checkCompany*/
    /*Метод получает объект авто(with(complect)) и проверяет подходит ли этот автомобиль под акцию*/
    /*в случае если авто подходит то возвращается имстина, иначе ложь*/
    public function checkCompany($car)
    {
        $status = 0;//статус подхода акции к автомобилю, если 0 то не подходит, если 1 то подходит. По дефолту 0

        $vin = $car->vin;//вин номер автомобиля
        $model = $car->model_id;//ид модели авто
        $complect = $car->complect_id;//ид комплектации
        $transmission = $car->complect->motor->transmission_id;//ид трансмиссии
        $wheel = $car->complect->motor->wheel_id;//ид привода
        $price = $car->totalPrice();//стоимость авто

        if(!empty($this->positive))//если не пусты включающие условия акции
            foreach ($this->positive as $key => $positive) ://прохожусь по всем включающим условиям акции
                $k = 0; //счётчик для подсчёта количкства совпадений параметров машины с параметрами условия включения
                $totalFields = $positive->countFields(); //кол-во параметров в условий
                ($vin           == $positive->vin)               ?   $k++    :   '';//если совпало вин номер авто с вин номером в условии
                ($model         == $positive->model_id)          ?   $k++    :   '';
                ($complect      == $positive->complect_id)       ?   $k++    :   '';
                ($transmission  == $positive->transmission_id)   ?   $k++    :   '';
                ($wheel         == $positive->wheel_id)          ?   $k++    :   '';
                ($price         >= $positive->pricestart  && $positive->pricestart!='')        ?   $k++    :   '';
                ($price         >= $positive->pricefinish && $positive->pricefinish!='')       ?   $k++    :   '';
                if($totalFields == $k) ://если счётчик == количеству параметров в условии
                    $status = 1; //то статус = 1
                    break; //завершаю обход, так как она уже 100% подходит
                endif;
            endforeach;

        if($status == 1 && !empty($this->negative))//если не пусты исключающие условия акции
            foreach ($this->negative as $key => $negative) ://прохожусь по всем исключающим условиям акции
                $k = 0;//счётчик для подсчёта количкства совпадений параметров машины с параметрами условия исключения
                $totalFields = $positive->countFields();//кол-во параметров в условий
                ($vin           == $positive->vin)               ?   $k++    :   '';
                ($model         == $positive->model_id)          ?   $k++    :   '';
                ($complect      == $positive->complect_id)       ?   $k++    :   '';
                ($transmission  == $positive->transmission_id)   ?   $k++    :   '';
                ($wheel         == $positive->wheel_id)          ?   $k++    :   '';
                ($price         >= $positive->pricestart  && $positive->pricestart!='')        ?   $k++    :   '';
                ($price         >= $positive->pricefinish && $positive->pricefinish!='')       ?   $k++    :   '';
                if($totalFields == $k) ://если счётчик == количеству параметров в условии исключения
                    $status = 0; //то даже если эта акция подходила , она будет исключена тк нашлось исключающее условие
                    break;//завершаю обход, так как она уже 100% НЕ подходит
                endif;
            endforeach;
        if($status==0) return false;//если статус = 0 значит не подходит
        return true;
    }

    public function exception()
    {
    	return $this->hasMany('App\company_data','company_id','id');
    }
    public function positive()
    {
        return $this->hasMany('App\company_data','company_id','id')->where('type',1);
    }
    public function negative()
    {
        return $this->hasMany('App\company_data','company_id','id')->where('type',0);
    }

    public function dops()
    {
    	return $this->hasMany('App\company_dop','company_id','id');
    }

    public function getStatus()
    {
        $mas[0] = "Спрятана";
        $mas[1] = "Открыта";
        return $mas;
    }
    public function getRazdels()
    {
    	$mas[1] = 'Скидка';
    	$mas[2] = 'Подарок';
    	$mas[3] = 'Сервис';
    	$mas[4] = 'Акция';
    	return $mas;
    }

    public function getScenario()
    {
    	$mas[1] = 'Расчёт';
    	$mas[2] = 'Бюджет';
    	$mas[3] = 'Номенклатура';
    	$mas[4] = 'Описание';
    	return $mas;
    }

    public function getMains()
    {
    	$mas[0] = 'Лояльна к остальным';
    	$mas[1] = 'Отключает остальные';
    	return $mas;
    }

    public function getImmortals()
    {
    	$mas[0] = 'Зависима от остальных';
    	$mas[1] = 'Независима от остальных';
    	return $mas;
    }

    public function getSumVal($car,$res=0)
    {
        $base = $car->complect->price;
        $pack = $car->packPrice();
        $dops = $car->dopprice;

        if($this->scenario==1)
        {
            if($this->base)
                $base = $base*($this->base/100);
            if($this->option)
                $pack = $pack*($this->option/100);
            if($this->dop)
                $dops = $dops*($this->dop/100);
            $this->sum = $base+$pack+$dops;
            if($this->max && $this->max<$this->sum)
                $this->sum = $this->max;
            return;
        }
        if($this->scenario==2)
        {
            $this->sum = $this->bydget;
            return;
        }
        $this->sum = 0;
        return;
    }

    public function getSumRep($car, $res=0)
    {
        $base = $car->complect->price;
        $pack = $car->packPrice();
        $dops = $car->dopprice;
        if($this->reparation_type==1)
        {
            if($this->reparation_base)
                $base = $base*($this->reparation_base/100);
            if($this->reparation_pack)
                $pack = $pack*($this->reparation_pack/100);
            if($this->reparation_dops)
                $dops = $dops*($this->reparation_dops/100);
            $this->repsum = $base+$pack+$dops;
        }
        if($this->reparation_type==2)
            $this->repsum = $this->reparation_bydget;
        return;
    }

    //находит все компании под автомобиль указанный в параметре
    public static function clientCompanyForHtml($selectionCar)
    {
        //получил авто с его данными
        $car = avacar::with('complect')->with('model')->with('packs')->with('dops')->where('id',$selectionCar->car_id)->first();
        $car->complect->motor;//дополучил мотор авто
        $companies = company::getActualCompany();//тащу все активные компании которые начались но не закончились
        foreach ($companies as $key => $item) {//прохожусь по найденым компаниям
            if(!$item->checkCompany($car))//если компания не подходит под авто
            {
                unset($companies[$key]);//то удаляю из массива компании эту компанию
                continue;
            }
            $item->selected = crm_worklist_company::where('wl_id',$selectionCar->worklist_id)->where('company_id',$item->id)->first();
            $item->getSumVal($car);
            $item->getSumRep($car);
        }
        
        $sales = $companies->filter(function($item){
            if($item->razdel==1)
                return $item;
            if($item->razdel==4)
                return $item;
        })->toArray();
        $sales = array_values($sales);

        $gifts = $companies->filter(function($item){
            return $item['razdel']==2;
        })->toArray();
        $gifts = array_values($gifts);

        $services = $companies->filter(function($item){
            return $item['razdel']==3;
        })->toArray();
        $services = array_values($services);

        return [$sales,$gifts,$services];
    }
}
