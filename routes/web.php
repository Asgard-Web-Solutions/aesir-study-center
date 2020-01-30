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

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('/exams', 'TestController@sets')->name('tests')->middleware('auth');
Route::get('/exam/{id}', 'TestController@select')->name('select-test')->middleware('auth');
Route::post('/exam/{id}/start', 'TestController@start')->name('start-test')->middleware('auth');
Route::get('/test/{id}', 'TestController@test')->name('take-test')->middleware('auth');
