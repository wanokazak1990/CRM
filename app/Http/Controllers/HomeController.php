<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\avacar;
use App\oa_brand;
use App\oa_model;
use Charts;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = oa_brand::get();
        foreach ($brands as $key => $brand) {
            $mas['count'][] = avacar::where('brand_id',$brand->id)->count();
            $mas['name'][] = $brand->name;
            $models[$brand->name] = oa_model::select('id','name')->where('brand_id',$brand->id)->get();
        }

        $chartBrand = Charts::create('donut', 'fusioncharts')
            ->title('Бренды')
            ->labels($mas['name'])
            ->values($mas['count'])
            ->elementLabel('Бренды')
            ->dimensions(400,400);

        unset($mas);
        
        foreach ($models as $brand_name => $brand_model) {
            foreach ($brand_model as $key => $model) {
                $count = avacar::where('model_id',$model->id)->count();
                $mas[$brand_name]['count'][] = $count;
                $mas[$brand_name]['name'][] = $model->name;
            }
        }

        foreach ($mas as $brand_name => $item) {
            $chartModels[] = Charts::create('donut', 'fusioncharts')
                ->title($brand_name)
                ->labels($item['name'])
                ->values($item['count'])
                ->elementLabel("Модели ".$brand_name)
                ->responsive(false)
                ->dimensions(400,400);
        }
         

        return view('home')
            ->with('title','CMS - Главная')
            ->with('chartBrand',$chartBrand)
            ->with('chartModel',$chartModels);
    }
}
