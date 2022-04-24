<?php

namespace App\Http\Controllers;

use App\Classes\Repositories\CategoryRepository;
use App\Models\Category;
use Auth;
use Illuminate\Http\Request;

class PagesWebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getNew(
        CategoryRepository $category_repo,
        Request $request
    ) {
        $user = Auth::user();
        $categories = Category::where('user_id', $user->custom_id)->get();
        $category_id = $request->cat_id;

        $category = Category::where('custom_id', $category_id)
            ->where('user_id', $user->custom_id)
            ->first();

        if ($category === null) {
            $category = $category_repo->getUserCategory(null, $user);
        }

        $data = [
            'active_category' => $category,
            'categories' => $categories,
        ];

        return view('pages.new', $data);
    }

    public function getNewPart2(Request $request)
    {
        $category_id = session('new_page_category_id');
        $page_html = session('new_page_html');

        if ($category_id === null || $page_html === null) {
            return redirect()->to('/pages/new');
        }

        $user = Auth::user();
        $categories = Category::where('user_id', $user->custom_id)->get();

        $category = Category::where('custom_id', $category_id)
            ->where('user_id', $user->custom_id)
            ->first();

        if ($category === null) {
            $category = $category_repo->getUserCategory(null, $user);
        }

        $data = [
            'active_category' => $category,
            'categories' => $categories,
        ];

        return view('pages.new-part-2', $data);
    }

    public function postCreate(Request $request)
    {
        dd($request->all());
    }

    public function postNewHtml(Request $request)
    {
        $user = Auth::user();

        $category = Category::where(
            'custom_id', $request->category_id
        )->where('user_id', $user->custom_id)
        ->first();

        if ($category === null) {
            $category = $category_repo->getUserCategory(null, $user);
        }

        session(['new_page_category_id' => $category->custom_id]);
        session(['new_page_html' => $request->page_html]);

        return redirect()->to('/pages/new-part-2');
    }
}
