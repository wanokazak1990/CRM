<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\type_model;//модель страны из таблицы country_models

/*
TypeModelController - обрабатывает все операции с созданием, редактированием и удалением типов моделей (кузовов).
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!ДЛЯ ОТОБРАЖЕНИЯ ВИВОВ ИСПОЛЬЗУЕТ ВИВ БРЕНДОВ, ТАК КАК ПОХОЖЫ!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/
class TypeModelController extends Controller
{
    //Список всех типов кузовов
	public function list()
	{
		$type = new type_model();
		$list = $type->get();//получаю все типы
		return view('typemodel.brandlist')//вывод вива списка типов
			->with('list',$list)//список типов
			->with('title','Список типов авто')//заголовок
			->with(['addTitle'=>'Новый тип','route'=>'typemodeladd'])
			->with('edit','typemodeledit')
			->with('delete','typemodeldelete');
	}

	//Создание нового типа (вывод формы)
	public function add()
	{
		return view('typemodel.brandadd')//вывод вива создания типа
			->with('title','Новый тип')
			->with('type',new type_model());
	}

	//Создание нового типа (запись данных из формы в бд)
	public function put(Request $request)
	{
		if(isset($_POST['submit']))//если нажат сабмит
		{
			$type = new type_model($request->input());
			$type->save();
			foreach ($request->file() as $key=>$file) {
     			$name = $key.$type->id.'.'.pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
     			$type->icon = $name;
     			$file->move(storage_path('app/public/typemodel/'), $name);
            }
            $type->update();
		}
		return redirect()->route('typemodellist');//перенаправляем на список типов (имя роута typemodellist)
	}

	//Редактирование типов (вывод формы)
	public function edit($id)
	{
		$type = new type_model();
		$type = $type->find($id);//ищу тип по id
		return view('typemodel.brandadd')//вывод вива
			->with('title','Редактирование типа')//заголовок
			->with('type',$type);
	}

	//Редактирование типа (запись данных из формы в бд)
	public function update(Request $request,$id)
	{
		if(isset($_POST['submit']))//если нажат сабмит
		{
			$type = new type_model();
			$type = $type->find($id);//ищу тип по id
			$type->name = $_POST['name'];//перезаписываю в модели тип имя тип из поста
			foreach ($request->file() as $key=>$file) {
     			$name = $key.$type->id.'.'.pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
     			$type->icon = $name;
     			$file->move(storage_path('app/public/typemodel/'), $name);
            }
			$type->save();//пересохраняю модель в бд
		}
		return redirect()->route('typemodellist');//перенаправляем на список типов (имя роута countrylist)
	}

	//Удаление типа (Вывод формы проверки)
	public function delete($id)
	{
		$type = new type_model();
		$type = $type->find($id);//ищу тип по id
		return view('typemodel.branddel')//вывод вива
			->with('title','Удаление типа')//заголовок
			->with('brand',$type);
	}

	//Удаление типа (выполнение)
	public function destroy($id)
	{
		if(isset($_POST['delete']))//если нажат делете
		{
			$type = type_model::find($id);
			@unlink(storage_path('app/public/typemodel/'.$type->icon));
			type_model::destroy($id);//удаляю жестко
		}
		return redirect()->route('typemodellist');//перенаправляем на список брендов (имя роута brandlist)
	}
}
