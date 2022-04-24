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
}
