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
Route::get('/division', 'PostsController@division')->name('division');

Route::get('/view_files', 'ViewForSavingController@deleteViewFile');
Route::post('/view_files', 'ViewForSavingController@viewFiles')->name('view_files');

Route::get('/', 'PostsController@index')->name('index');
Route::post('/store', 'PostsController@store')->name('store');
Route::get('/get/{id}', 'PostsController@edit')->name('edit');
Route::put('/update/{id}', 'PostsController@update')->name('update');
Route::delete('destroy/{id}', 'PostsController@destroy')->name('destroy');

Route::get('/view/{id}', 'PostsController@view')->name('view');
Route::get('/download/{id}', 'PostsController@download')->name('download');

Auth::routes();

Route::resource('users', 'UserController');
Route::resource('roles', 'RoleController');
Route::resource('permissions', 'PermissionController');
Route::resource('divisions', 'DivisionController');

Route::get('/users/password/{id}', 'UserController@editPassword');
Route::put('/users/password/{id}', 'UserController@updatePassword')->name('users.password');