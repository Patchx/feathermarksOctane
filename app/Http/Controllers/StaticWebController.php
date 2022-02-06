<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticWebController extends Controller
{
    public function getIndex()
    {
    	return view('main-landing-page');
    }
}
