<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Auth;
use Illuminate\Http\Request;

class PagesAjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postDelete($page_id)
    {
        $user = Auth::user();

        $page = Page::where('user_id', $user->custom_id)
            ->where('custom_id', $page_id)
            ->first();

        if ($page === null) {
            return json_encode(['status' => 'page_not_found']);
        }

        $page->delete();

        return json_encode(['status' => 'success']);
    }
}
