<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\country_model;//модель страны из таблицы country_models

/*
CountryController - обрабатывает все операции с созданием, редактированием и удалением стран.
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!ДЛЯ ОТОБРАЖЕНИЯ ВИВОВ ИСПОЛЬЗУЕТ ВИВ БРЕНДОВ, ТАК КАК ПОХОЖЫ!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/

class CountryController extends Controller
{
    //Список всех стран
	public function list()
	{
		$country = new country_model();
		$list = $country->get();//получаю все страны
		return view('country.brandlist')//вывод вива списка стран
			->with('list',$list)//список стран
			->with('title','Список стран')//заголовок
			->with(['addTitle'=>'Новая страна','route'=>'countryadd'])
			->with('edit','countryedit')
			->with('delete','countrydelete');
	}

	//Создание нового страны (вывод формы)
	public function add()
	{	
		return view('country.brandadd')//вывод вива создания стран
			->with('title','Новая страна')
			->with('country',new country_model());
	}

	//Создание нового страны (запись данных из формы в бд)
	public function put(Request $request)
	{
		if(isset($_POST['submit']))//если нажат сабмит
		{
			$country = new country_model($request->input());
			$country->save();			
			foreach ($request->file() as $key=>$file) {
     			$name = $key.$country->id.'.'.pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
     			$country->flag = $name;
     			$file->move(storage_path('app/public/country/'), $name);
            }
            $country->update();
		}
		return redirect()->route('countrylist');//перенаправляем на список стран (имя роута countrylist)
	}

	//Редактирование странs (вывод формы)
	public function edit($id)
	{
		$country = new country_model();
		$country = $country->find($id);//ищу странe по id
		return view('country.brandadd')//вывод вива
			->with('title','Редактирование страны')//заголовок
			->with('brand',$country);
	}

	//Редактирование страны (запись данных из формы в бд)
	public function update(Request $request,$id)
	{
		if(isset($_POST['submit']))//если нажат сабмит
		{
			$country = new country_model();
			$country = $country->find($id);//ищу стран по id
			$country->name = $_POST['name'];//перезаписываю в модели страны имя страны из поста
			foreach ($request->file() as $key=>$file) {
     			$name = $key.$country->id.'.'.pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
     			$country->flag = $name;
     			$file->move(storage_path('app/public/country/'), $name);
            }
			$country->save();//пересохраняю модель в бд
		}
		return redirect()->route('countrylist');//перенаправляем на список стран (имя роута countrylist)
	}

	//Удаление страны (Вывод формы проверки)
	public function delete($id)
	{
		$country = new country_model();
		$country = $country->find($id);//ищу страну по id
		return view('country.branddel')//вывод вива
			->with('title','Удаление страны')//заголовок
			->with('brand',$country);
	}

	//Удаление страны (выполнение)
	public function destroy($id)
	{
		if(isset($_POST['delete']))//если нажат делете
		{
			$country = country_model::find($id);//ищу country по id
			@unlink(storage_path('app/public/country/'.$country->flag));
			country_model::destroy($id);//удаляю жестко
		}
		return redirect()->route('countrylist');//перенаправляем на список брендов (имя роута brandlist)
	}
}
