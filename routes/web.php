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

// === AJAX routes ===

// ---------------------
// - LinkAjaxController -
// ---------------------

Route::post('/links/create', 'LinkAjaxController@postCreate');
Route::post('/links/delete/{link_id}', 'LinkAjaxController@postDelete');
Route::post('/links/edit/{link_id}', 'LinkAjaxController@postEdit');
Route::get('/links/my-links', 'LinkAjaxController@getMyLinks');
Route::post('/links/run-feather-command', 'LinkAjaxController@postRunFeatherCommand');
Route::get('/links/search-my-links', 'LinkAjaxController@getSearchMyLinks');

// ---------------------
// - UrlAjaxController -
// ---------------------

Route::get('/url/title/{url}', 'UrlAjaxController@getTitle');

// === non-AJAX routes ===

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// -----------------------
// - StaticWebController -
// -----------------------

Route::get('/search-results', 'StaticWebController@getSearchResults');
Route::get('/', 'StaticWebController@getIndex');
