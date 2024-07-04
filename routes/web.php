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

if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cctv/grid/{owner}/{status}/{publik}/{keyword}','CctvController@getGrid');
Route::get('/cctv/detail/{id}','CctvController@getDetail');
Route::post('/cctv/grid/{owner}/{status}/{publik}','CctvController@postGrid');
Route::get('/cctv/gridcari','CctvController@getGridCari');
Route::get('/tvkabel/cari','TvkabelController@getCari');
Route::post('/tvkabel/cari','TvkabelController@postCari');