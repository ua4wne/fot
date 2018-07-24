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

//bank/ группа обработки роутов справочника banks
Route::group(['prefix'=>'banks'], function(){
    Route::get('/',['uses'=>'References\BankController@index','as'=>'banks']);
    //banks/add
    Route::match(['get','post'],'/add',['uses'=>'References\BankController@create','as'=>'bankAdd']);
    //banks/edit
    Route::match(['get','post','delete'],'/edit/{bank}',['uses'=>'References\BankController@edit','as'=>'bankEdit']);
});

//organization/ группа обработки роутов organization
Route::group(['prefix'=>'organization'], function(){
    Route::get('/',['uses'=>'OrganizationController@index','as'=>'organizations']);
    Route::get('/view/{org}',['uses'=>'OrganizationController@view','as'=>'orgView']);
    //organization/add
    Route::match(['get','post'],'/add',['uses'=>'OrganizationController@create','as'=>'orgAdd']);
    //organization/edit
    Route::match(['get','post','delete'],'/edit/{org}',['uses'=>'OrganizationController@edit','as'=>'orgEdit']);
});

//division/ группа обработки роутов divisions
Route::group(['prefix'=>'divisions'], function(){
    Route::get('/',['uses'=>'DivisionController@index','as'=>'divisions']);
    //divisions/add
    Route::match(['get','post'],'/add',['uses'=>'DivisionController@create','as'=>'divisionAdd']);
    //divisions/ajax/add
    Route::post('/ajax/add',['uses'=>'Ajax\DivisionController@create','as'=>'newDivision']);
    //divisions/ajax/edit
    Route::post('/ajax/edit',['uses'=>'Ajax\DivisionController@edit','as'=>'editDivision']);
    //divisions/ajax/delete
    Route::post('/ajax/delete',['uses'=>'Ajax\DivisionController@delete','as'=>'deleteDivision']);
    //divisions/edit
    Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'DivisionController@edit','as'=>'divisionEdit']);
});

//bacc/ группа обработки роутов bacc
Route::group(['prefix'=>'bacc'], function(){
    Route::get('/',['uses'=>'BAccountsController@index','as'=>'bacc']);
    //bacc/add
    Route::match(['get','post'],'/add',['uses'=>'BAccountsController@create','as'=>'baccAdd']);
    //bacc/edit
    Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'BAccountsController@edit','as'=>'baccEdit']);
    //bacc/ajax/add
    Route::post('/ajax/add',['uses'=>'Ajax\BAccountController@create','as'=>'newAccount']);
    //bacc/ajax/edit
    Route::post('/ajax/edit',['uses'=>'Ajax\BAccountController@edit','as'=>'editAccount']);
    //bacc/ajax/delete
    Route::post('/ajax/delete',['uses'=>'Ajax\BAccountController@delete','as'=>'deleteAccount']);
});

//groups/ группа обработки роутов groups
Route::group(['prefix'=>'groups'], function(){
    Route::get('/',['uses'=>'GroupController@index','as'=>'groups']);
    Route::get('/view/{id}',['uses'=>'GroupController@view','as'=>'groupView']);
    //group/add
    Route::match(['get','post'],'/add',['uses'=>'GroupController@create','as'=>'groupAdd']);
    //group/edit
    Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'GroupController@edit','as'=>'groupEdit']);
    //groups/ajax/add
    Route::post('/ajax/add',['uses'=>'Ajax\GroupController@create','as'=>'newGroup']);
    //groups/ajax/edit
    Route::post('/ajax/edit',['uses'=>'Ajax\GroupController@edit','as'=>'editGroup']);
    //groups/ajax/delete
    Route::post('/ajax/delete',['uses'=>'Ajax\GroupController@delete','as'=>'deleteGroup']);
});

//firms/ группа обработки роутов firms
Route::group(['prefix'=>'firms'], function(){
    Route::get('/',['uses'=>'FirmController@index','as'=>'firms']);
    //firms/physical
    Route::match(['get','post'],'/physical',['uses'=>'FirmController@physical','as'=>'physical']);
    //firms/legal_entity
    Route::match(['get','post'],'/legal_entity',['uses'=>'FirmController@legal','as'=>'legal_entity']);
    //firms/add
    Route::match(['get','post'],'/add',['uses'=>'FirmController@create','as'=>'firmAdd']);
    //firms/edit
    Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'FirmController@edit','as'=>'firmEdit']);
    //firms/ajax/edit
    Route::post('/ajax/edit',['uses'=>'Ajax\FirmController@edit','as'=>'editFirm']);
    //firms/ajax/delete
    Route::post('/ajax/delete',['uses'=>'Ajax\FirmController@delete','as'=>'deleteFirm']);

});