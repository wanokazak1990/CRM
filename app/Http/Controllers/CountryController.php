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
		return view('brand.brandlist')//вывод вива списка стран
			->with('list',$list)//список стран
			->with('title','Список стран')//заголовок
			->with(['addTitle'=>'Новая страна','route'=>'countryadd'])
			->with('edit','countryedit')
			->with('delete','countrydelete');
	}

	//Создание нового страны (вывод формы)
	public function add()
	{
		return view('brand.brandadd')//вывод вива создания стран
			->with('title','Новая страна');
	}

	//Создание нового страны (запись данных из формы в бд)
	public function put()
	{
		if(isset($_POST['submit']))//если нажат сабмит
		{
			$country = new country_model();
			$country->create($_POST);//записываем данные из поста в модель и заливаем модель в БД 
		}
		return redirect()->route('countrylist');//перенаправляем на список стран (имя роута countrylist)
	}

	//Редактирование странs (вывод формы)
	public function edit($id)
	{
		$country = new country_model();
		$country = $country->find($id);//ищу странe по id
		return view('brand.brandadd')//вывод вива
			->with('title','Редактирование страны')//заголовок
			->with('brand',$country);
	}

	//Редактирование страны (запись данных из формы в бд)
	public function update($id)
	{
		if(isset($_POST['submit']))//если нажат сабмит
		{
			$country = new country_model();
			$country = $country->find($id);//ищу стран по id
			$country->name = $_POST['name'];//перезаписываю в модели страны имя страны из поста
			$country->save();//пересохраняю модель в бд
		}
		return redirect()->route('countrylist');//перенаправляем на список стран (имя роута countrylist)
	}

	//Удаление страны (Вывод формы проверки)
	public function delete($id)
	{
		$country = new country_model();
		$country = $country->find($id);//ищу страну по id
		return view('brand.branddel')//вывод вива
			->with('title','Удаление страны')//заголовок
			->with('brand',$country);
	}

	//Удаление страны (выполнение)
	public function destroy($id)
	{
		if(isset($_POST['delete']))//если нажат делете
		{
			country_model::destroy($id);//удаляю жестко
		}
		return redirect()->route('countrylist');//перенаправляем на список брендов (имя роута brandlist)
	}
}
