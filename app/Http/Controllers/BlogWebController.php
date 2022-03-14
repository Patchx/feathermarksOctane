<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogWebController extends Controller
{
    public function getIndex()
    {
        return view('blog.index');
    }

    public function getSetFeathermarksAsTheNewTabPageInBrave()
    {
        $data = [
            'html_title' => 'Set Feathermarks as the new tab page in Brave',
        ];

        return view('blog.set-feathermarks-as-the-new-tab-page-in-brave', $data);
    }
}
