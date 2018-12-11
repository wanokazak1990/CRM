<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\file;
use App\file_type;
use App\oa_model;
use App\oa_brand;

class FileController extends Controller
{
    public function list()
    {
    	$file = new file();
    	$list = $file->get();

    	return view('files.list')
    		->with('title', 'Список файлов')
    		->with(['addTitle'=>'Новый файл','route'=>'filesadd'])
    		->with('list', $list)
    		->with('edit', 'filesedit')
    		->with('delete', 'filesdelete');
    }

    public function add()
    {
    	$oa_model = oa_model::pluck('name','id');
    	$file_type = file_type::pluck('name','id');
    	$brands = oa_brand::pluck('name','id');
    	return view('files.add')
    		->with('title', 'Добавить новый файл')
    		->with('models', $oa_model)
    		->with('types', $file_type)
    		->with('brands',$brands);
    }

    public function put(Request $request)
    {
    	if (isset($_POST['submit']))
    	{
    		$file = new file($_POST); // модель файла, а не загружаемый!!
			if ($request->hasFile('file'))
    		{
    			$loadfile = $request->file('file'); // а вот это загружаемый!!
    			$destinationPath = storage_path().'/app/public/model_docs/'; // Путь, куда загрузим файл
    			$prefix = str_random(8);
                $filename = $prefix.'_'.$loadfile->getClientOriginalName(); // В "file" файла запишем название файла
    			$file->file = $filename;
    			$request->file('file')->move($destinationPath, $filename);
    		}
    		$file->save();
    	}
    	return redirect()->route('fileslist');
    }

    public function edit($id)
    {
    	$file = new file();
    	$file = $file->find($id);

    	$oa_model = oa_model::where('brand_id', $file->brand_id)->pluck('name','id');
    	$file_type = file_type::pluck('name','id');
        $brands = oa_brand::pluck('name','id');

    	return view('files.edit')
    		->with('title', 'Редактирование файла')
    		->with('file', $file)
    		->with('models', $oa_model)
    		->with('types', $file_type)
            ->with('brands',$brands);
    }

    public function update(Request $request, $id)
    {
    	if (isset($_POST['submit']))
    	{
    		$file = new file();
	    	$file = $file->find($id);
	    	$file->name = $_POST['name'];
	    	$file->type_id = $_POST['type_id'];
	    	$file->model_id = $_POST['model_id'];
            $file->brand_id = $_POST['brand_id'];
	    	if ($request->hasFile('file'))
    		{
    			$loadfile = $request->file('file'); 
    			$destinationPath = storage_path().'/app/public/model_docs/'; // Путь, куда загрузим файл
                $prefix = substr($file->file, 0, 8);
    			
    			@unlink(storage_path('/app/public/model_docs/'.$file->file)); // Удаляем предыдущий файл, который относился к текущей записи

    			$filename = $prefix.'_'.$loadfile->getClientOriginalName(); // В "file" файла запишем название НОВОГО файла
    			$file->file = $filename;
    			$request->file('file')->move($destinationPath, $filename);
    		}
	    	$file->save();
    	}    	
    	return redirect()->route('fileslist');
    }

    public function delete($id)
    {
        $file = new file();
        $file = $file->find($id);

        return view('files.del')
            ->with('title', 'Удаление файла')
            ->with('file', $file);
    }

    public function destroy($id)
    {
        if(isset($_POST['delete']))
        {
            $file = file::find($id);
            @unlink(storage_path('/app/public/model_docs/'.$file->file));
            file::destroy($id);
        }
        return redirect()->route('fileslist');
    }
}
