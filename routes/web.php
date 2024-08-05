<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SetController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExamSetController;
use App\Http\Controllers\ExamSessionController;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('exam')->name('exam.')->controller(ExamSetController::class)->group(function () {
    Route::get('/public', 'public')->name('public');
    Route::get('/{set}', 'view')->name('view');
});


Route::prefix('boyager')->group(function () {
    Voyager::routes();
});

Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/exams', 'exams')->name('exams');
    Route::get('/myexams', 'myexams')->name('myexams');
    Route::post('/update', 'update')->name('update');
    Route::post('/changepass', 'changepass')->name('changepass');
});

Route::get('/transcripts/{user}', [ProfileController::class, 'view'])->name('profile.view');

Route::prefix('test')->name('exam-session.')->controller(ExamSessionController::class)->middleware('auth')->group(function () {
    Route::get('/{set}/start', 'start')->name('start');
    Route::get('/{set}/configure', 'configure')->name('configure');
    Route::post('/{set}/store', 'store')->name('store');
    Route::get('/{set}/test', 'test')->name('test');
    Route::post('/{set}/answer', 'answer')->name('answer');
    Route::get('/{set}/answer', 'answerRedirect')->name('answer-break');
    Route::get('/{set}/summary', 'summary')->name('summary');
});

Route::prefix('practice')->name('practice.')->controller(PracticeController::class)->middleware('auth')->group(function () {
    Route::get('/{set}/start', 'start')->name('start');
    Route::get('/{set}/settings', 'settings')->name('settings');
    Route::post('/{set}/begin', 'begin')->name('begin');
    Route::get('/{set}/review', 'review')->name('review');
    Route::get('/{set}/next', 'next')->name('next');
    Route::get('/{set}/previous', 'previous')->name('previous');
    Route::get('/{set}/done', 'done')->name('done');
});

Route::prefix('admin')->name('admin.')->controller(AdminController::class)->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/users', 'users')->name('users');
    Route::get('/users/{user}', 'user')->name('user');
    Route::post('/users/{user}/update', 'userUpdate')->name('user-update');
});

Route::get('/colors', [HomeController::class, 'colors'])->name('colors');

Route::get('/architect/new', [SetController::class, 'create'])->name('exam-create')->middleware('auth');
Route::post('/architect/add', [SetController::class, 'store'])->name('save-exam')->middleware('auth');
Route::post('/architect/{set}/update', [SetController::class, 'update'])->name('update-exam')->middleware('auth');

Route::get('/oldexam/{id}', [TestController::class, 'select'])->name('select-test')->middleware('auth');
Route::post('/oldexam/{id}/start', [TestController::class, 'start'])->name('start-test')->middleware('auth');
Route::get('/test/{id}', [TestController::class, 'test'])->name('take-test')->middleware('auth');
Route::post('/test/{id}', [TestController::class, 'answer'])->name('answer')->middleware('auth');

Route::get('/history/{id}', [HomeController::class, 'history'])->name('test-history')->middleware('auth');

Route::get('/architect', [QuestionController::class, 'exams'])->name('manage-exams')->middleware('auth');
Route::get('/manage/{id}', [QuestionController::class, 'index'])->name('manage-questions')->middleware('auth');
Route::get('/manage/{id}/add', [QuestionController::class, 'add'])->name('add-question')->middleware('auth');
Route::post('/manage/{id}/add', [QuestionController::class, 'store'])->name('save-question')->middleware('auth');
Route::get('/manage/{id}/edit', [QuestionController::class, 'edit'])->name('edit-question')->middleware('auth');
Route::post('/manage/{id}/update', [QuestionController::class, 'update'])->name('update-question')->middleware('auth');
Route::get('/manage/{set}/newGroup', [GroupController::class, 'create'])->name('group-create')->middleware('auth');
Route::post('/manage/{set}/storeGroup', [GroupController::class, 'store'])->name('group-store')->middleware('auth');

Route::get('/questionGroup/{group}', [GroupController::class, 'show'])->name('group-view')->middleware('auth');
Route::post('/questionGroup/{group}/update', [GroupController::class, 'update'])->name('group-update')->middleware('auth');
Route::post('/questionGroup/{group}/addQuestions', [GroupController::class, 'storeQuestions'])->name('group-store-questions')->middleware('auth');
Route::get('/questionGroup/{group}/editQuestion/{question}', [GroupController::class, 'editQuestion'])->name('group-edit-question')->middleware('auth');
Route::post('/questionGroup/{group}/updateQuestion/{question}', [GroupController::class, 'updateQuestion'])->name('group-update-question')->middleware('auth');
Route::get('/questionGroup/{group}/deleteQuestion/{question}', [GroupController::class, 'deleteQuestion'])->name('group-delete-question')->middleware('auth');
Route::post('/questionGroup/{group}/removeQuestion/{question}', [GroupController::class, 'removeQuestion'])->name('group-remove-question')->middleware('auth');

Route::get('/question/{id}', [QuestionController::class, 'answers'])->name('manage-answers')->middleware('auth');
Route::post('/question/{id}/add', [QuestionController::class, 'storeAnswer'])->name('save-answers')->middleware('auth');
Route::get('/answer/{id}/edit', [QuestionController::class, 'editAnswer'])->name('edit-answer')->middleware('auth');
Route::post('/answer/{id}/edit', [QuestionController::class, 'updateAnswer'])->name('update-answer')->middleware('auth');
Route::get('/answer/{id}/delete', [QuestionController::class, 'deleteAnswer'])->name('delete-answer')->middleware('auth');
Route::post('/answer/{id}/delete', [QuestionController::class, 'deleteAnswerConfirm'])->name('delete-answer-confirm')->middleware('auth');

// Route::get('/practice/{set}', [PracticeController::class, 'start'])->name('practice-start')->middleware('auth');
// Route::get('/practice/{set}/config', [PracticeController::class, 'config'])->name('practice-config')->middleware('auth');
// Route::post('/practice/{set}/config', [PracticeController::class, 'configUpdate'])->name('practice-config-update')->middleware('auth');