<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/templates/create', 'TemplateController@index');
//Route::get('/templates', 'TemplateController@store');


//Route::get('/dashboard', 'DashboardController@index');
//Route::post('/parser', ['as' => 'auth::parser', 'uses' => 'ParserController@init', 'middleware' => 'auth']);
Route::get('/parser', ['as' => 'auth::parser', 'uses' => 'ParserController@init', 'middleware' => 'auth']);
//Route::post('/parser/list-new-ads', ['as' => 'auth::parser-list-ads', 'uses' => 'ParserController@listNewAds', 'middleware' => 'auth']);
Route::post('/ads/fetch', ['as' => 'auth::ads-fetch', 'uses' => 'AdController@fetch', 'middleware' => 'auth']);

Route::group(['as' => 'auth::', 'middleware' => 'auth'], function () {
    Route::resource('templates', 'TemplateController');
});
