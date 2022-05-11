<?php

namespace App\Http\Controllers;

use App\Classes\Repositories\CategoryRepository;
use App\Http\Requests\CreatePageRequest;
use App\Models\Category;
use App\Models\Link;
use App\Models\Page;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class PagesWebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('getPage');
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

    public function getPage($page_id)
    {
        $page = Page::where('custom_id', $page_id)->first();

        if ($page === null) {
            abort(404);
        }

        $data = [
            'page' => $page,
        ];

        if ($page->visibility === 'anyone') {
            return view('pages.page', $data);
        }

        $user = Auth::user();

        if ($user === null
            || $user->custom_id !== $page->user_id
        ) {
            abort(403);
        }

        return view('pages.page', $data);
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

    public function postCreate(CreatePageRequest $request)
    {
        $user = Auth::user();

        $category = Category::where('custom_id', $request->category_id)
            ->where('user_id', $user->custom_id)
            ->first();

        if ($category === null) {
            abort(403);
        }

        $save_result = $this->savePageAndLink($request, $user);

        return redirect()->to('/home')->with(
            'success_msg', "Page created successfully!"
        );
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

    // -------------------
    // - Private Methods -
    // -------------------

    private function savePageAndLink(Request $request, User $user)
    {
        DB::transaction(function() use ($request, $user) {
            $page = Page::create([
                'user_id' => $user->custom_id,
                'html' => $request->page_html,
                'visibility' => $request->visibility_level,
            ]);

            $instaopen_command = $request->instaopen_command;

            if ($instaopen_command === null) {
                $instaopen_command = '';
            }

            if ($instaopen_command === '') {
                $instaopen_command = trim($request->instaopen_command, ' /');

                Link::where('user_id', $user->custom_id)
                    ->where('instaopen_command', $instaopen_command)
                    ->where('category_id', $request->category_id)
                    ->update(['instaopen_command' => '']);
            }

            $link = Link::create([
                'category_id' => $request->category_id,
                'instaopen_command' => $instaopen_command,
                'name' => $request->page_name,
                'page_id' => $page->custom_id,
                'search_phrase' => $request->search_keywords,
                'url' => env('APP_URL') . '/pages/' . $page->custom_id,
                'user_id' => $user->custom_id,
            ]);

            $page->link_id = $link->custom_id;
            $page->save();

            return [
                'link' => $link,
                'page' => $page,
                'status' => 'success',
            ];
        });
    }
}
