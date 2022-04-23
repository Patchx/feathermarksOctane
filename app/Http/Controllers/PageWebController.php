<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageWebController extends Controller
{
    public function getNew(Request $request)
    {
        dd($request->all());
    }
}
