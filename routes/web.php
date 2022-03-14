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

Route::post('/links/create', 'App\Http\Controllers\LinkAjaxController@postCreate');
Route::post('/links/delete/{link_id}', 'App\Http\Controllers\LinkAjaxController@postDelete');
Route::post('/links/edit/{link_id}', 'App\Http\Controllers\LinkAjaxController@postEdit');
Route::get('/links/frequently-used/{category_id}', 'App\Http\Controllers\LinkAjaxController@getFrequentlyUsed');
Route::post('/links/run-feather-command', 'App\Http\Controllers\LinkAjaxController@postRunFeatherCommand');
Route::get('/links/search-my-links', 'App\Http\Controllers\LinkAjaxController@getSearchMyLinks');
Route::post('/links/track-click', 'App\Http\Controllers\LinkAjaxController@postTrackClick');

// ---------------------
// - UrlAjaxController -
// ---------------------

Route::get('/url/title/{url}', 'App\Http\Controllers\UrlAjaxController@getTitle');

// === non-AJAX routes ===

Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

// ---------------------
// - BlogWebController -
// ---------------------

Route::get('/blog', "{$route_prefix}\BlogWebController@getIndex");
Route::get('/blog/set-feathermarks-as-the-new-tab-page-in-brave', 'App\Http\Controllers\BlogWebController@getSetFeathermarksAsTheNewTabPageInBrave');

// -----------------------
// - StaticWebController -
// -----------------------

Route::get('/', 'App\Http\Controllers\StaticWebController@getIndex');
