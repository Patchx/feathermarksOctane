<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Classes\Repositories\CategoryRepository;

use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(
        CategoryRepository $category_repo,
        Request $request
    ) {
        $user = Auth::user();
        $categories = Category::where('user_id', $user->custom_id)->get();

        $active_category = $category_repo->getUserCategory(
            $request->cat_id, $user
        );
        
        $user->latest_category_id = $active_category->custom_id;
        $user->save();

        if ($request->cat_id === null) {
            $html_title = 'FeatherMarks';
        } else {
            $html_title = 'FeatherMarks - ' . ucwords($active_category->name);
        }

        $data = [
            'active_category' => $active_category,
            'categories' => $categories,
            'html_title' => $html_title,
        ];

        return view('home', $data);
    }
}
