<?php

namespace App\Http\Controllers;

use App\Classes\Repositories\CategoryRepository;
use App\Models\Category;
use Auth;
use Illuminate\Http\Request;

class CategoryWebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getIndex(
        CategoryRepository $category_repo,
        Request $request
    ) {
        $user = Auth::user();
        $categories = Category::where('user_id', $user->custom_id)->get();

        $active_category = $category_repo->getUserCategory(
            $request->cat_id, $user
        );

        $data = [
            'active_category' => $active_category,
            'categories' => $categories,
            'html_title' => 'Categories',
        ];

        return view('categories.index', $data);
    }

    public function postIndex(Request $request)
    {
        foreach ($request->all() as $key => $name) {
            if (!str_contains($key, 'category-')) {
                continue;
            }

            $category_id = str_replace('category-', '', $key);

            Category::where('custom_id', $category_id)->update([
                'name' => $name,
            ]);
        }

        return redirect()->back()->with('success_msg', "Categories updated!");
    }
}
