<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\character;

class CharacterController extends Controller
{
    public function list()
    {
    	$character = new character();
    	$list = $character->get();

    	return view('character.list')
    		->with('title', 'Список характеристик')
    		->with(['addTitle'=>'Новая характеристика','route'=>'characteradd'])
    		->with('list', $list)
    		->with('edit', 'characteredit')
    		->with('delete', 'characterdel');
    }

    public function add()
    {
    	return view('character.add')
    		->with('title', 'Новая характеристика');
    }

    public function put()
    {
    	if (isset($_POST['submit']))
    	{
    		$character = new character();
    		$character->create($_POST);
    	}
    	return redirect()->route('characterlist');
    }

    public function edit($id)
    {
    	$character = new character();
    	$character = $character->find($id);

    	return view('character.edit')
    		->with('title', 'Редактирование характеристики')
    		->with('character', $character);
    }

    public function update($id)
    {
    	if (isset($_POST['submit']))
    	{
    		$character = new character();
    		$character = $character->find($id);
    		$character->name = $_POST['name'];
    		$character->save();
    	}
    	return redirect()->route('characterlist');
    }

    public function delete($id)
    {
    	$character = new character();
    	$character = $character->find($id);

    	return view('character.del')
    		->with('title', 'Удаление характеристики')
    		->with('character', $character);
    }

    public function destroy($id)
    {
    	if (isset($_POST['delete']))
    	{
    		character::destroy($id);
    	}
    	return redirect()->route('characterlist');
    }
}
