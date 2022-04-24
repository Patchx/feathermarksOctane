<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$route_prefix = 'App\Http\Controllers';

// === AJAX routes ===

// ----------------------
// - LinksAjaxController -
// ----------------------

Route::post('/links/create', "{$route_prefix}\LinksAjaxController@postCreate");
Route::post('/links/delete/{link_id}', "{$route_prefix}\LinksAjaxController@postDelete");
Route::post('/links/edit/{link_id}', "{$route_prefix}\LinksAjaxController@postEdit");
Route::get('/links/frequently-used/{category_id}', "{$route_prefix}\LinksAjaxController@getFrequentlyUsed");
Route::post('/links/run-feather-command', "{$route_prefix}\LinksAjaxController@postRunFeatherCommand");
Route::get('/links/search-my-links', "{$route_prefix}\LinksAjaxController@getSearchMyLinks");
Route::post('/links/track-click', "{$route_prefix}\LinksAjaxController@postTrackClick");

// ---------------------
// - UrlAjaxController -
// ---------------------

Route::get('/url/title/{url}', "{$route_prefix}\UrlAjaxController@getTitle");

// === non-AJAX routes ===

Auth::routes();

Route::get('/home', "{$route_prefix}\HomeController@index")->name('home');

// ---------------------
// - BlogWebController -
// ---------------------

Route::get('/blog', "{$route_prefix}\BlogWebController@getIndex");
Route::get('/blog/set-feathermarks-as-the-new-tab-page-in-brave', "{$route_prefix}\BlogWebController@getSetFeathermarksAsTheNewTabPageInBrave");

// ---------------------
// - PageWebController -
// ---------------------

Route::post('/pages/create', "{$route_prefix}\PagesWebController@postCreate");
Route::get('/pages/new', "{$route_prefix}\PagesWebController@getNew");
Route::post('/pages/new-html', "{$route_prefix}\PagesWebController@postNewHtml");
Route::get('/pages/new-part-2', "{$route_prefix}\PagesWebController@getNewPart2");

// -----------------------
// - StaticWebController -
// -----------------------

Route::get('/', "{$route_prefix}\StaticWebController@getIndex");
