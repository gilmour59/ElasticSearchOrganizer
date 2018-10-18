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

Route::prefix('admin')->group(function(){
    Route::get('/', 'AdminController@index')->name('admin');
    Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
});

Route::get('/division', 'PostsController@division')->name('division');

Route::get('/view_files', 'ViewForSavingController@deleteViewFile')->middleware('auth');
Route::post('/view_files', 'ViewForSavingController@viewFiles')->name('view_files')->middleware('auth');

Route::get('/', 'PostsController@index')->name('index')->middleware('auth');
Route::post('/store', 'PostsController@store')->name('store')->middleware('auth');
Route::get('/get/{id}', 'PostsController@edit')->name('edit')->middleware('auth');
Route::put('/update/{id}', 'PostsController@update')->name('update')->middleware('auth');
Route::delete('destroy/{id}', 'PostsController@destroy')->name('destroy')->middleware('auth');

Route::get('/view/{id}', 'PostsController@view')->name('view')->middleware('auth');
Route::get('/download/{id}', 'PostsController@download')->name('download')->middleware('auth');

Auth::routes();

Route::resource('users', 'UserController');
Route::resource('roles', 'RoleController');
Route::resource('permissions', 'PermissionController');
