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

/* Документация */
Route::get('/documentation', 'PostController@documentation');


//Route::get('/dashboard', 'DashboardController@index');
//Route::post('/parser', ['as' => 'auth::parser', 'uses' => 'ParserController@init', 'middleware' => 'auth']);
//Route::get('/parser', ['as' => 'auth::parser', 'uses' => 'ParserController@init', 'middleware' => 'auth']);
//Route::post('/parser/list-new-ads', ['as' => 'auth::parser-list-ads', 'uses' => 'ParserController@listNewAds', 'middleware' => 'auth']);

/* Тестовая страница для различных данных */
Route::get('/test', 'PostController@test');

Route::get('/template/{templateId}/ads/{adId}', ['as' => 'auth::ad-description', 'uses' => 'AdController@index', 'middleware' => 'auth']);

/* Экспорт */
Route::post('/export', ['as' => 'auth::ads.export', 'uses' => 'AdController@export', 'middleware' => 'auth']);

/* Страницы со списками обьявлений */
Route::get('/template/{templateId}/ads/', ['as' => 'auth::ads-list-done', 'uses' => 'AdController@adsListDone', 'middleware' => 'auth']);
Route::get('/template/{templateId}/ads-queue/', ['as' => 'auth::ads-list-queue', 'uses' => 'AdController@adsListQueue', 'middleware' => 'auth']);
Route::get('/template/{templateId}/ads-ignored/', ['as' => 'auth::ads-list-ignored', 'uses' => 'AdController@adsListIgnored', 'middleware' => 'auth']);

/* Получение url объявлений и постановка их в очередь на парсинг */
Route::get('/fetch', ['as' => 'fetch', 'uses' => 'AdController@fetch', 'middleware' => 'auth']);

/* Парсинг обьявлений из очереди */
Route::get('/parse', ['as' => 'parse', 'uses' => 'AdController@parse', 'middleware' => 'auth']);

/* Контроллеры запросов */
Route::post('/templates/control', ['as' => 'auth::templates.control', 'uses' => 'TemplateController@control', 'middleware' => 'auth']);
Route::get('/templates/all', ['as' => 'auth::templates.all', 'uses' => 'TemplateController@all', 'middleware' => 'auth']);
Route::group(['as' => 'auth::', 'middleware' => 'auth'], function () {
    Route::resource('templates', 'TemplateController');
});


