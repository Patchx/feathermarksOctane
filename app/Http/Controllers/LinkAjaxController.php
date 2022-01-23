<?php

namespace App\Http\Controllers;

use App\Classes\Repositories\CategoryRepository;
use App\Http\Requests\CreateLinkRequest;
use App\Http\Requests\EditLinkRequest;
use App\Models\Category;
use App\Models\Link;
use Auth;
use Illuminate\Http\Request;

class LinkAjaxController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function getMyLinks(
        CategoryRepository $category_repo,
        Request $request
    ) {
        $user = Auth::user();
        $category = $category_repo->getUserCategory($request->cat_id, $user);

        $links = Link::where('user_id', $user->custom_id)
            ->where('category_id', $category->custom_id)
            ->get();

        return json_encode([
            'status' => 'success',
            'links' => $links,
        ]);
    }

    // - This package is broken for this project, but you can use it's examples for how to write the SQL queries:
    // - https://github.com/yabhq/laravel-scout-mysql-driver
    // --
    public function getSearchMyLinks(
        CategoryRepository $category_repo,
        Request $request
    ) {
        $user = Auth::user();
        $category = $category_repo->getUserCategory($request->cat_id, $user);
        $user_search = $request->q;

        $links = Link::where('user_id', $user->custom_id)
            ->where('category_id', $category->custom_id)
            ->where(function($query) use ($user_search) {
                $query->where('name', 'like', '%' . $user_search . '%')
                    ->orWhere('url', 'like', '%' . $user_search . '%')
                    ->orWhere('search_phrase', 'like', '%' . $user_search . '%');
            })
            ->get();

        return json_encode([
            'status' => 'success',
            'links' => $links,
        ]);
    }

    public function postCreate(
        CategoryRepository $category_repo,
        CreateLinkRequest $request
    ) {
    	$user = Auth::user();
        $url = $request->url;

        if (!$this->urlStartsWithProtocol($url)) {
            $url = '//' . $url;
        }

        $category = $category_repo->getUserCategory(
            $request->category_id, $user
        );

        $instaopen_command = trim($request->instaopen_command, ' /');

        if ($instaopen_command !== '') {
            Link::where('user_id', $user->custom_id)
                ->where('instaopen_command', $instaopen_command)
                ->where('category_id', $category->custom_id)
                ->update(['instaopen_command' => '']);
        }

    	$new_link = Link::create([
    		'user_id' => $user->custom_id,
    		'folder_id' => null,
    		'category_id' => $category->custom_id,
    		'name' => $request->name,
    		'url' => $url,
            'search_phrase' => $request->search_phrase,
            'instaopen_command' => $instaopen_command,
    	]);

        return json_encode([
            'status' => 'success',
            'link' => $new_link,
        ]);
    }

    public function postDelete($link_id)
    {
        $user = Auth::user();

        $link = Link::where('custom_id', $link_id)
            ->where('user_id', $user->custom_id)
            ->first();

        if ($link !== null) {
            $link->delete();
        }

        return json_encode(['status' => 'success']);
    }

    public function postEdit(
        EditLinkRequest $request,
        $link_id
    ) {
        $user = Auth::user();

        $link = Link::where('custom_id', $link_id)
            ->where('user_id', $user->custom_id)
            ->first();

        if ($link === null) {
            return json_encode(['status' => 'link_not_found']);
        }

        $instaopen_command = trim($request->instaopen_command, ' /');

        $link->name = $request->name;
        $link->url = $request->url;
        $link->search_phrase = $request->search_phrase;
        $link->instaopen_command = $instaopen_command;
        $link->save();

        return json_encode(['status' => 'success']);
    }

    // Assuming only instaopen commands for now
    // --
    public function postRunFeatherCommand(
        CategoryRepository $category_repo,
        Request $request
    ) {
        $user = Auth::user();

        $category = $category_repo->getUserCategory(
            $request->category_id, $user
        );

        $command = trim($request->command, ' /');

        $link = Link::where('user_id', $user->custom_id)
            ->where('instaopen_command', $command)
            ->where('category_id', $category->custom_id)
            ->first();

        if ($link === null) {
            return json_encode(['status' => 'command_not_found']);
        }

        return json_encode([
            'status' => 'success',
            'directive' => 'open_link',
            'url' => $link->url,
        ]);
    }

    public function postTrackClick(Request $request)
    {
        $now = now();
        $user = Auth::user();

        $link = Link::where('custom_id', $request->link_id)
            ->where('user_id', $user->custom_id)
            ->first();

        if ($link === null) {
            return json_encode(['status' => 'link_not_found']);
        }

        $recent_uses = json_decode($link->recent_uses);

        if (count($recent_uses) > 0
            && $now->timestamp - $recent_uses[0] < 200
        ) {
            return json_encode(['status' => 'clicked_too_recently']);
        }

        while (count($recent_uses) > 4) {
            array_pop($recent_uses);
        }

        array_unshift($recent_uses, $now->timestamp);
        $link->recent_uses = json_encode($recent_uses);
        $link->save();

        return json_encode(['status' => 'success']);
    }

    // -------------------
    // - Private Methods -
    // -------------------

    private function urlStartsWithProtocol($url)
    {
        if (strpos($url, "https://") === 0
            || strpos($url, "http://") === 0
        ) {
            return true;
        }

        return $url[0] === '/' && $url[1] === '/';
    }
}
