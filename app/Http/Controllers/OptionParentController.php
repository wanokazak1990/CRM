<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\option_parent;

/*
OptionParentController - обрабатывает все операции с созданием, редактированием и удалением разделов опций.
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!!!!!!!ДЛЯ ОТОБРАЖЕНИЯ ВИВОВ ИСПОЛЬЗУЕТ ВИВ БРЕНДОВ, ТАК КАК ПОХОЖЫ!!!!!!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
*/

class OptionParentController extends Controller
{
   	//Список всех разделов
	public function list()
	{
		$parent = new option_parent();
		$list = $parent->get();//получаю все разделов
		return view('optionparent.brandlist')//вывод вива списка разделов
			->with('list',$list)//список разделов
			->with('title','Список разделов оборудования')//заголовок
			->with(['addTitle'=>'Новый раздел оборудования','route'=>'optparentadd'])
			->with('edit','optparentedit')
			->with('delete','optparentdelete');
	}

	//Создание нового бренда (вывод формы)
	public function add()
	{
		return view('optionparent.brandadd')//вывод вива создания бренда
			->with('title','Новый раздел оборудования');//заголовок
	}

	//Создание нового бренда (запись данных из формы в бд)
	public function put()
	{
		if(isset($_POST['submit']))//если нажат сабмит
		{
			$parent = new option_parent();
			$parent->create($_POST);//записываем данные из поста в модель и заливаем модель в БД 
		}
		return redirect()->route('optparentlist');//перенаправляем на список брендов (имя роута brandlist)
	}

	//Редактирование бренда (вывод формы)
	public function edit($id)
	{
		$parent = new option_parent();
		$parent = $parent->find($id);//ищу бренд по id
		return view('optionparent.brandadd')//вывод вива
			->with('title','Редактирование бренда')//заголовок
			->with('brand',$parent);//модель бренда по id
	}

	//Редактирование бренда (запись данных из формы в бд)
	public function update($id)
	{
		if(isset($_POST['submit']))//если нажат сабмит
		{
			$parent = new option_parent();
			$parent = $parent->find($id);//ищу бренд по id
			$parent->name = $_POST['name'];//перезаписываю в модели бренд имя бренда из поста
			$parent->save();//пересохраняю модель в бд
		}
		return redirect()->route('optparentlist');//перенаправляем на список брендов (имя роута brandlist)
	}

	//Удаление бренда (Вывод формы проверки)
	public function delete($id)
	{
		$parent = new option_parent();
		$parent = $parent->find($id);//ищу бренд по id
		return view('optionparent.branddel')//вывод вива
			->with('title','Удаление раздела оборудования')//заголовок
			->with('brand',$parent);//модель бренда
	}

	//Удаление бренда (выполнение)
	public function destroy($id)
	{
		if(isset($_POST['delete']))//если нажат делете
		{
			option_parent::destroy($id);//удаляю жестко
		}
		return redirect()->route('optparentlist');//перенаправляем на список брендов (имя роута brandlist)
	}
}
