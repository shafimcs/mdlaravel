<?php

use Illuminate\Support\Facades\Route;

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


 
Route::group(['middleware' => ['auth'],'prefix'=>'admin'], function () {
	
	 Route::get('/admin', 'AdminController@index')->name('admin.index');
	
	 Route::get('/country', 'admin\CountryController@create')->name('country.create');
    Route::post('/country/store', 'admin\CountryController@store')->name('country.store');
    Route::get('/country/show', 'admin\CountryController@show')->name('country.show');
    Route::get('/country/{id}', 'admin\CountryController@edit')->name('country.edit');
    Route::post('/country/update', 'admin\CountryController@update')->name('country.update');
    Route::delete('/country/{id}', 'admin\CountryController@destroy')->name('country.destroy');
	
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/index', 'HomeController@create')->name('home.create');