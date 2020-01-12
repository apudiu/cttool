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
//    return view('welcome');
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
// doing it here for the sake of time
Route::post('/home/process', 'HomeController@bulkUpload')->name('home.upload');

Route::prefix('csv')->group(function() {
    Route::get('/', 'CsvDataController@index')->name('csv.index');
    Route::post('/store', 'CsvDataController@store')->name('csv.store');
    Route::delete('/delete', 'CsvDataController@destroy')->name('csv.delete');
});

Route::prefix('img')->group(function() {
    Route::get('/', 'FileController@index')->name('img.index');
    Route::post('/store', 'FileController@store')->name('img.store');
});

Route::prefix('setting')->group(function() {
    Route::get('/', 'SettingController@index')->name('setting.index');
    Route::post('/update', 'SettingController@update')->name('setting.update');
});

Route::prefix('report')->group(function() {
    Route::get('/', 'ReportController@index')->name('report.index');
});

Route::prefix('audit')->group(function() {
    Route::get('logs', 'AuditLogController@index')->name('audit.logs');
});
