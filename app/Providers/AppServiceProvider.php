<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        /**/
        //ВКЛЮЧИТЬ ОТЛАДКУ ЗАПРОСОВ
        /*DB::listen(function($query){
            dump($query->sql);
        });
        /**/
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
