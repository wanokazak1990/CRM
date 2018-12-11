<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CRMMainController extends Controller
{
    public function main()
    {
    	return view('crm.main')
    		->with('title', 'CRM "Учет"');
    }
}
