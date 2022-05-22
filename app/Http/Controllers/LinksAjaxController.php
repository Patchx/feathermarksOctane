<?php

namespace App\Http\Controllers;

use App\Classes\ApiWrappers\MeilisearchWrapper;
use App\Classes\Repositories\CategoryRepository;
use App\Classes\Repositories\LinkRepository;
use App\Http\Requests\CreateLinkRequest;
use App\Http\Requests\EditLinkRequest;
use App\Jobs\AddToLinkUsage;
use App\Models\Category;
use App\Models\Link;
use App\Models\Page;
use Auth;
use Illuminate\Http\Request;

class LinksAjaxController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function getAllLinks($category_id)
    {
        $user = Auth::user();

        $category = Category::where('custom_id', $category_id)
            ->where('user_id', $user->custom_id)
            ->first();

        if ($category === null) {
            return json_encode(['status' => 'category_not_found']);
        }

        $links = Link::where('user_id', $user->custom_id)
            ->where('category_id', $category->custom_id)
            ->orderBy('name')
            ->get();

        return json_encode([
            'status' => 'success',
            'links' => $links,
        ]);
    }

    public function getFrequentlyUsed(
        LinkRepository $link_repo,
        $category_id
    ) {
        $user = Auth::user();

        $category = Category::where('custom_id', $category_id)
            ->where('user_id', $user->custom_id)
            ->first();

        if ($category === null) {
            return json_encode(['status' => 'category_not_found']);
        }

        $high_usage_links = $link_repo->frequentlyUsedLinks($category_id);

        return json_encode([
            'status' => 'success',
            'links' => $high_usage_links,
        ]);
    }

    public function getSearchMyLinks(
        CategoryRepository $category_repo,
        MeilisearchWrapper $meilisearch,
        Request $request
    ) {
        $user = Auth::user();
        $category = $category_repo->getUserCategory($request->cat_id, $user);
        $user_search = $request->q;

        $meili_results = $meilisearch->searchLinks([
            'query' => $request->q,
            'user_id' => $user->custom_id,
            'category_id' => $category->custom_id,
        ])->hits;
        
        $link_ids = array_map(function($result) {
            return $result->id;
        }, $meili_results);

        $links = Link::where('user_id', $user->custom_id)
            ->where('category_id', $category->custom_id)
            ->whereIn('custom_id', $link_ids)
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

        $page_id = $this->getPageIdFromUrl($url);

    	$new_link = Link::create([
    		'category_id' => $category->custom_id,
            'instaopen_command' => $instaopen_command,
    		'name' => $request->name,
            'page_id' => $page_id,
            'search_phrase' => $request->search_phrase,
    		'url' => $url,
            'user_id' => $user->custom_id,
    	]);

        return json_encode([
            'status' => 'success',
            'link' => $new_link,
        ]);
    }

    public function postDelete(
        LinkRepository $link_repo,
        $link_id
    ) {
        $user = Auth::user();

        $link = Link::where('custom_id', $link_id)
            ->where('user_id', $user->custom_id)
            ->first();

        if ($link === null) {
            return json_encode(['status' => 'success']);
        }

        $deleting_my_page_link = $link_repo->linkPointsToMyPage(
            $link, $user->custom_id
        );
        
        $link->delete();

        return json_encode([
            'status' => 'success',
            'deleted_my_page_link' => $deleting_my_page_link,
            'page_id' => $link->page_id,
        ]);
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

        $url = $request->url;

        if (!$this->urlStartsWithProtocol($url)) {
            $url = '//' . $url;
        }

        $instaopen_command = trim($request->instaopen_command, ' /');

        $link->name = $request->name;
        $link->url = $url;
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

        AddToLinkUsage::dispatch($link, now()->timestamp);

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

        AddToLinkUsage::dispatch($link, $now->timestamp);

        return json_encode(['status' => 'success']);
    }

    // -------------------
    // - Private Methods -
    // -------------------

    private function getPageIdFromUrl($url)
    {
        $page_prefix = env('APP_URL') . '/pages/';
        $page_id = str_replace($page_prefix, '', $url);
        $page = Page::where('custom_id', $page_id)->first();

        if ($page === null) {
            return null;
        }

        return $page->custom_id;
    }

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
