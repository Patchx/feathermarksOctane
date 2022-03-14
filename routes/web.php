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
// - LinkAjaxController -
// ----------------------

Route::post('/links/create', "{$route_prefix}\LinkAjaxController@postCreate");
Route::post('/links/delete/{link_id}', "{$route_prefix}\LinkAjaxController@postDelete");
Route::post('/links/edit/{link_id}', "{$route_prefix}\LinkAjaxController@postEdit");
Route::get('/links/frequently-used/{category_id}', "{$route_prefix}\LinkAjaxController@getFrequentlyUsed");
Route::post('/links/run-feather-command', "{$route_prefix}\LinkAjaxController@postRunFeatherCommand");
Route::get('/links/search-my-links', "{$route_prefix}\LinkAjaxController@getSearchMyLinks");
Route::post('/links/track-click', "{$route_prefix}\LinkAjaxController@postTrackClick");

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

// -----------------------
// - StaticWebController -
// -----------------------

Route::get('/', "{$route_prefix}\StaticWebController@getIndex");
