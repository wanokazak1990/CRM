<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\file_type;

class FileTypeController extends Controller
{
    public function list()
    {
    	$file_type = new file_type();
    	$list = $file_type->get();
    	return view('types.list')
    		->with('title', 'Список типов файлов')
    		->with(['addTitle'=>'Новый тип','route'=>'typesadd'])
    		->with('list', $list)
    		->with('edit', 'typesedit')
    		->with('delete', 'typesdelete');
    }

    public function add()
    {
    	return view('types.add')
    		->with('title', 'Добавить новый тип');
    }

    public function put(Request $request)
    {
    	if (isset($_POST['submit']))
    	{
    		$file_type = new file_type($_POST);
			if ($request->hasFile('file'))
    		{
    			$file = $request->file('file');
    			$destinationPath = storage_path().'/app/public/images/file_types/'; // Путь, куда загрузим иконку
    			$filename = str_random(8) . '.' . $file->getClientOriginalExtension(); // Новое имя файла
    			$file_type->icon = $filename; // В "icon" файла запишем название иконки
    			$request->file('file')->move($destinationPath, $filename); // Загружаем иконку в указанную директорию
    		}
    		$file_type->save();
    	}
    	return redirect()->route('typeslist');
    }

    public function edit($id)
    {
    	$file_type = new file_type();
    	$file_type = $file_type->find($id);

    	return view('types.edit')
    		->with('title', 'Редактирование типа')
    		->with('type', $file_type);
    }

    public function update(Request $request, $id)
    {
    	if (isset($_POST['submit']))
    	{
    		$file_type = new file_type();
    		$file_type = $file_type->find($id);
			$file_type->name = $_POST['name'];
			if ($request->hasFile('file'))
    		{
    			$file = $request->file('file');
    			$destinationPath = storage_path().'/app/public/images/file_types/'; // Путь, куда загрузим иконку
    			$filename = $file_type->icon; // Берем имя предыдущей иконки, чтобы её перезаписать новой
    			$request->file('file')->move($destinationPath, $filename); // Загружаем иконку в указанную директорию
    		}
			$file_type->save();
    	}
    	return redirect()->route('typeslist');
    }

    public function delete($id)
    {
    	$file_type = new file_type();
    	$file_type = $file_type->find($id);

    	return view('types.del')
    		->with('title', 'Удаление типа')
    		->with('type', $file_type);
    }

    public function destroy($id)
    {
    	if(isset($_POST['delete']))
		{
			$file_type = file_type::find($id);
            @unlink(storage_path('/app/public/images/file_types/'.$file_type->icon));
            file_type::destroy($id);
		}
    	return redirect()->route('typeslist');
    }
}
