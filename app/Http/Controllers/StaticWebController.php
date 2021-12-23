<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticWebController extends Controller
{
    public function getIndex()
    {
    	return view('welcome');
    }

    public function getSearchResults()
    {
    	return view('search-results');
    }
}
