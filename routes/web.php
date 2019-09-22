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

Route::get('/home', 'HomeController@index')->name('home');
// doing it here for the sake of time
Route::post('/home/process', 'HomeController@bulkUpload')->name('home.upload');

Route::prefix('csv')->group(function() {
    Route::get('/', 'CsvDataController@index')->name('csv.index');
    Route::post('/store', 'CsvDataController@store')->name('csv.store');
});

Route::prefix('img')->group(function() {
    Route::get('/', 'FileController@index')->name('img.index');
    Route::post('/store', 'FileController@store')->name('img.store');
});

Route::prefix('setting')->group(function() {
    Route::get('/', 'SettingController@index')->name('setting.index');
    Route::post('/store', 'SettingController@store')->name('setting.store');
});
