<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
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

// Route::get('/', function () {
//     return view('login');
// });
// Route::get('/online-checkliste-create', function() {
//     return view('online-checkliste-create');
// });
Route::get('/boarding-unterlagen-new', function() {
    return view('boarding-unterlagen-new');
});
Route::get('/online-checkliste', function() {
    return view('online-checkliste');
});
Route::get('/dashboard', function() {
    return view('dashboard');
});
Route::get('/detail', function() {
    return view('detail-task');
});
Route::get('/task-manager', function() {
    return view('task-manager');
});
Route::get('/new-task', function() {
    return view('new-task');
});
Route::get('/', function() {
    return view('login');
});
// Route::get('/uploadfile-new', function() {
//     return view('uploadfile');
// });
// Route::post('fileUpload', [
//    'as' => 'image.add',
//    'uses' => 'UploadController@fileUpload'
// ]);
Route::get('paginate', 'App\Http\Controllers\PaginationController@index');

require __DIR__.'/auth.php';

Route::get('/', '\App\Http\Controllers\Auth\LoginController@showLoginForm')->name('showLogin');
Route::post('/login', '\App\Http\Controllers\Auth\LoginController@customLogin')->name('login.post'); 
Route::post('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::get('/register', '\App\Http\Controllers\Auth\LoginController@getRegister')->name('get.register');
Route::post('/register', '\App\Http\Controllers\Auth\LoginController@postRegister')->name('post.register');

Route::group(['middleware' => ['CheckIdAdmin']], function () {
    Route::prefix('users')->group(function () {
        Route::get('/', '\App\Http\Controllers\UsersController@index');
        Route::post('/store', '\App\Http\Controllers\UsersController@store');
        Route::get('/create', '\App\Http\Controllers\UsersController@create');
        Route::get('/edit/{user}', '\App\Http\Controllers\UsersController@edit');
        Route::put('/update/{user}', '\App\Http\Controllers\UsersController@update');
    });
    Route::post('/delete', '\App\Http\Controllers\UsersController@delete_user');
});
Route::post('/save-group', '\App\Http\Controllers\UsersController@save_group');
Route::post('/load-group-modal', '\App\Http\Controllers\UsersController@load_group_modal');
Route::post('/load-user', '\App\Http\Controllers\UsersController@load_user_group');
Route::post('/open-user-group', '\App\Http\Controllers\UsersController@open_user_group');
Route::post('/save-new-form', '\App\Http\Controllers\SaveData@save_new_form');
Route::post('/open-popup', '\App\Http\Controllers\UsersController@open_popup');
Route::post('/create_new_group', '\App\Http\Controllers\UsersController@create_new_group');


Route::group(['prefix' => 'boarding-unterlagen', 'middleware' => ['auth.basic']], function(){
    Route::get('/', '\App\Http\Controllers\CheckListController@index');
    Route::get('/create', '\App\Http\Controllers\CheckListController@create')->name('create.checklist');
    Route::post('/store', '\App\Http\Controllers\CheckListController@store')->name('store.checklist');
    Route::post('/update/{id}', '\App\Http\Controllers\CheckListController@update')->name('update.checklist');
    Route::delete('/destroy/{id}', '\App\Http\Controllers\CheckListController@destroy')->name('destroy.checklist');
    Route::get('/edit/{id}', '\App\Http\Controllers\CheckListController@edit')->name('edit.checklist');
});

//route checktask
Route::post('/checktask', '\App\Http\Controllers\UsersController@postCheckTask')->name('post.task');