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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('/exams', 'TestController@sets')->name('tests')->middleware('auth');
Route::get('/exam/{id}', 'TestController@select')->name('select-test')->middleware('auth');
Route::post('/exam/{id}/start', 'TestController@start')->name('start-test')->middleware('auth');
Route::get('/test/{id}', 'TestController@test')->name('take-test')->middleware('auth');
Route::post('/test/{id}', 'TestController@answer')->name('answer')->middleware('auth');

Route::get('/history/{id}', 'HomeController@history')->name('test-history')->middleware('auth');

Route::get('/manage', 'QuestionController@exams')->name('manage-exams')->middleware('auth');
Route::get('/manage/{id}', 'QuestionController@index')->name('manage-questions')->middleware('auth');
Route::get('/manage/{id}/add', 'QuestionController@add')->name('add-question')->middleware('auth');
Route::post('/manage/{id}/add', 'QuestionController@store')->name('save-question')->middleware('auth');

Route::get('/question/{id}', 'QuestionController@answers')->name('manage-answers')->middleware('auth');
Route::post('/question/{id}/add', 'QuestionController@storeAnswer')->name('save-answers')->middleware('auth');
Route::get('/answer/{id}/edit', 'QuestionController@editAnswer')->name('edit-answer')->middleware('auth');
Route::post('/answer/{id}/edit', 'QuestionController@updateAnswer')->name('update-answer')->middleware('auth');
Route::get('/answer/{id}/delete', 'QuestionController@deleteAnswer')->name('delete-answer')->middleware('auth');
Route::post('/answer/{id}/delete', 'QuestionController@deleteAnswerConfirm')->name('delete-answer-confirm')->middleware('auth');

Route::post('/exam/add', 'QuestionController@storeExam')->name('save-exam')->middleware('auth');
