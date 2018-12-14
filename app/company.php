<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    //
    protected $fillable = ['day_in','day_out','status','timer','razdel','name','scenario','base','option','dop','value','max','valute','bydget','file','title','text','ofer','main','immortal'];

    public function exception()
    {
    	return $this->hasMany('App\company_data','company_id','id');
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
}
