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

/*Route::get('/', function () {
    return view('welcome');
}); */

Route::middleware(['auth'])->group(function(){
    //Route::get('/', 'MainController@index');
});


Route::get('/', 'MainController@index')->name('main');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//currency/ группа обработки роутов справочника currency
Route::group(['prefix'=>'currency'], function(){
    Route::get('/',['uses'=>'References\CurrencyController@index','as'=>'currency']);

    //currency/add
    Route::match(['get','post'],'/add',['uses'=>'References\CurrencyController@create','as'=>'currencyAdd']);

    //currency/edit
    Route::match(['get','post','delete'],'/edit/{currency}',['uses'=>'References\CurrencyController@edit','as'=>'currencyEdit']);
});

