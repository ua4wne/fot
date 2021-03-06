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

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
//activate
Route::get('/activate','Auth\LoginController@activate');

Route::middleware(['auth'])->group(function(){

    Route::get('/', 'MainController@index')->name('main');
    // /ajax/balance_graph
    Route::post('/ajax/balance_graph',['uses'=>'Ajax\MainController@balance_graph','as'=>'balance_graph']);

    Route::group(['prefix'=>'exchange'], function() {
        Route::post('import-firm', 'FirmExcelController@importFirm')->name('importFirm');
        Route::get('export-firm/{type}', 'FirmExcelController@exportFirm')->name('exportFirm');
        Route::post('import-contract', 'ContractExcelController@importContract')->name('importContract');
        Route::get('export-contract/{type}', 'ContractExcelController@exportFirm')->name('exportContract');
        Route::post('import-code', 'CodeExcelController@importCode')->name('importCode');
        Route::post('import-cash_doc', 'DocExcelController@importCashDoc')->name('importCashDoc');
        Route::post('import-statements', 'DocExcelController@importStatements')->name('importStatements');
        Route::post('import-advances', 'DocExcelController@importAdvances')->name('importAdvances');
        Route::post('import-sales', 'DocExcelController@importSales')->name('importSales');
        Route::post('import-purchases', 'DocExcelController@importPurchases')->name('importPurchases');
    });

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

    //operation/ группа обработки роутов справочника operations
    Route::group(['prefix'=>'operations'], function(){
        Route::get('/',['uses'=>'References\TypeOperationController@index','as'=>'operations']);
        //operations/add
        Route::match(['get','post'],'/add',['uses'=>'References\TypeOperationController@create','as'=>'operationAdd']);
        //operations/edit
        Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'References\TypeOperationController@edit','as'=>'operationEdit']);
    });

    //typedocs/ группа обработки роутов справочника typedocs
    Route::group(['prefix'=>'typedocs'], function(){
        Route::get('/',['uses'=>'References\TypeDocController@index','as'=>'typedocs']);
        //typedocs/add
        Route::match(['get','post'],'/add',['uses'=>'References\TypeDocController@create','as'=>'typedocAdd']);
        //typedocs/edit
        Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'References\TypeDocController@edit','as'=>'typedocEdit']);
    });

    //settlements/ группа обработки роутов справочника settlements
    Route::group(['prefix'=>'settlements'], function(){
        Route::get('/',['uses'=>'References\SettlementController@index','as'=>'settlements']);
        //settlements/add
        Route::match(['get','post'],'/add',['uses'=>'References\SettlementController@create','as'=>'settlementAdd']);
        //settlements/edit
        Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'References\SettlementController@edit','as'=>'settlementEdit']);
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

    //contracts/ группа обработки роутов contracts
    Route::group(['prefix'=>'contracts'], function(){
        Route::get('/',['uses'=>'ContractController@index','as'=>'contracts']);
        Route::post('/view',['uses'=>'ContractController@view','as'=>'contractView']);
        Route::get('/view{id?}',['uses'=>'ContractController@view','as'=>'contractView']);
        //contracts/add
        Route::match(['get','post'],'/add',['uses'=>'ContractController@create','as'=>'contractAdd']);
        //contract/edit
        Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'ContractController@edit','as'=>'contractEdit']);
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

    //bstate/ группа обработки роутов bank_statements
    Route::group(['prefix'=>'bstate'], function(){
        Route::get('/',['uses'=>'BankStatementController@index','as'=>'bstate']);
        //bstate/add
        Route::match(['get','post'],'/add',['uses'=>'BankStatementController@create','as'=>'bstateAdd']);
        //bstate/edit
        Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'BankStatementController@edit','as'=>'bstateEdit']);
        //bstate/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\BankStatementController@create','as'=>'newBstate']);
        //bstate/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\BankStatementController@edit','as'=>'editBstate']);
        //bstate/ajax/delete
        Route::post('/ajax/delete',['uses'=>'Ajax\BankStatementController@delete','as'=>'deleteBstate']);
    });

    //groups/ группа обработки роутов groups
    Route::group(['prefix'=>'groups'], function(){
        Route::get('/',['uses'=>'GroupController@index','as'=>'groups']);
        Route::get('/view/{id}',['uses'=>'GroupController@view','as'=>'groupView']);
        //group/add
        Route::match(['get','post'],'/add',['uses'=>'GroupController@create','as'=>'groupAdd']);
        //group/firm_add
        Route::match(['get','post'],'/firm_add/{id}',['uses'=>'GroupController@firm_add','as'=>'groupFirmAdd']);
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

    //users/ группа обработки роутов users
    Route::group(['prefix'=>'users'], function(){
        Route::get('/',['uses'=>'Options\UserController@index','as'=>'users']);
        //users/add
        Route::match(['get','post'],'/add',['uses'=>'Options\UserController@create','as'=>'userAdd']);
        //users/edit
        Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'Options\UserController@edit','as'=>'userEdit']);
        //users/reset
        Route::get('/reset/{id}',['uses'=>'Options\UserController@resetPass','as'=>'userReset']);
        //users/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\UserController@switchLogin','as'=>'switchLogin']);
        //users/ajax/edit_login
        Route::post('/ajax/edit_login',['uses'=>'Ajax\UserController@editLogin','as'=>'editLogin']);
        //users/ajax/delete
        Route::post('/ajax/delete',['uses'=>'Ajax\UserController@delete','as'=>'deleteLogin']);
        //users/ajax/add_role
        Route::post('/ajax/add_role',['uses'=>'Ajax\UserController@addRole','as'=>'addRole']);
        //users/ajax/get_role
        Route::post('/ajax/get_role',['uses'=>'Ajax\UserController@getRole','as'=>'getRole']);
    });

    //roles/ группа обработки роутов roles
    Route::group(['prefix'=>'roles'], function(){
        Route::get('/',['uses'=>'Options\RoleController@index','as'=>'roles']);
        //roles/add
        Route::match(['get','post'],'/add',['uses'=>'Options\RoleController@create','as'=>'roleAdd']);
        //roles/edit
        Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'Options\RoleController@edit','as'=>'roleEdit']);
        //roles/ajax/get_action
        Route::post('/ajax/get_action',['uses'=>'Ajax\ActionController@getAction','as'=>'getAction']);
        //roles/ajax/add_action
        Route::post('/ajax/add_action',['uses'=>'Ajax\ActionController@addAction','as'=>'addAction']);
    });

    //actions/ группа обработки роутов actions
    Route::group(['prefix'=>'actions'], function(){
        Route::get('/',['uses'=>'Options\ActionController@index','as'=>'actions']);
        //actions/add
        Route::match(['get','post'],'/add',['uses'=>'Options\ActionController@create','as'=>'actionAdd']);
        //actions/edit
        Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'Options\ActionController@edit','as'=>'actionEdit']);
    });

    //codes/ группа обработки роутов codes
    Route::group(['prefix'=>'codes'], function(){
        Route::get('/',['uses'=>'CodeController@index','as'=>'codes']);
        //codes/add
        Route::match(['get','post'],'/add',['uses'=>'CodeController@create','as'=>'codeAdd']);
        //codes/edit
        Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'CodeController@edit','as'=>'codeEdit']);
        //codes/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\CodeController@edit','as'=>'editCode']);
        //codes/ajax/delete
        Route::post('/ajax/delete',['uses'=>'Ajax\CodeController@delete','as'=>'deleteCode']);
    });

    //cash_docs/ группа обработки роутов cash-docs
    Route::group(['prefix'=>'cash_docs'], function(){
        Route::get('/',['uses'=>'CashDocController@index','as'=>'cash_docs']);
        Route::post('/view',['uses'=>'CashDocController@view','as'=>'cash_docs_period']);
        //cash_docs/ajax/getorg
        Route::get('/ajax/getorg',['uses'=>'Ajax\CashDocController@ajaxData','as'=>'getOrg']);
        //cash_docs/add
        Route::match(['get','post'],'/add/{direction}',['uses'=>'CashDocController@create','as'=>'cashDocAdd']);
        //cash_docs/filter
        Route::get('/filter',['uses'=>'CashDocController@set_filter','as'=>'cashBookFilter']);
        //cash_docs/cash_book
        Route::post('/cash_book',['uses'=>'CashDocController@cash_book','as'=>'cash_book']);
        //cash/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\CashDocController@edit','as'=>'editCashDoc']);
        //cash/ajax/delete
        Route::post('/ajax/delete',['uses'=>'Ajax\CashDocController@delete','as'=>'delCashDoc']);
    });

    //statements/ группа обработки роутов statements
    Route::group(['prefix'=>'statements'], function(){
        Route::match(['get','post'],'/',['uses'=>'StatementController@index','as'=>'statements']);
        Route::get('/view/{id}',['uses'=>'StatementController@view','as'=>'statementView']);
        //statements/add
        Route::match(['get','post'],'/add/{direction}',['uses'=>'StatementController@create','as'=>'statementAdd']);
        //statements/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\StatementController@edit','as'=>'editStatement']);
        //statements/ajax/delete
        Route::post('/ajax/delete',['uses'=>'Ajax\StatementController@delete','as'=>'delStatement']);
        //statements/ajax/find_bacc
        Route::post('/ajax/find_bacc',['uses'=>'Ajax\StatementController@findBacc','as'=>'findBacc']);
        //statements/ajax/find_contract
        Route::post('/ajax/find_contract',['uses'=>'Ajax\StatementController@findContract','as'=>'findContract']);
        //statements/ajax/getparams
        Route::post('/ajax/getparams',['uses'=>'Ajax\StatementController@getParams','as'=>'ParamStatement']);
        //statements/filter
        Route::get('/filter',['uses'=>'StatementController@set_filter','as'=>'acctFilter']);
        //statements/acct
        Route::post('/acct',['uses'=>'StatementController@acct_report','as'=>'acctReport']);
        //statements/balance_filter
        Route::get('/balance_filter',['uses'=>'StatementController@balance_filter','as'=>'balanceFilter']);
        //statements/acct
        Route::post('/balance',['uses'=>'StatementController@balance_report','as'=>'balanceReport']);
    });

    //person/ группа обработки роутов справочника persons
    Route::group(['prefix'=>'persons'], function(){
        Route::get('/',['uses'=>'References\PersonController@index','as'=>'persons']);
        //persons/add
        Route::match(['get','post'],'/add',['uses'=>'References\PersonController@create','as'=>'personAdd']);
        //persons/edit
        Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'References\PersonController@edit','as'=>'personEdit']);
    });

    //advances/ группа обработки роутов advances
    Route::group(['prefix'=>'advances'], function(){
        Route::match(['get','post'],'/',['uses'=>'AdvanceController@index','as'=>'advances']);
        Route::get('/view/{id}',['uses'=>'AdvanceController@view','as'=>'advanceView']);
        //advances/add
        Route::get('/add',['uses'=>'AdvanceController@create','as'=>'advanceAdd']);
        //advances/clone
        Route::get('/clone/{id}',['uses'=>'AdvanceController@clone','as'=>'advanceClone']);
        //advances/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\AdvanceController@create','as'=>'addAdvance']);
        //advances/ajax/clone
        Route::post('/ajax/clone',['uses'=>'Ajax\AdvanceController@clone','as'=>'cloneAdvance']);
        //advances/ajax/addpos
        Route::post('/ajax/addpos',['uses'=>'Ajax\AdvanceController@addPosition','as'=>'addAdvancePos']);
        //advances/ajax/delete
        Route::post('/ajax/delete',['uses'=>'Ajax\AdvanceController@delete','as'=>'delAdvance']);
        //advances/ajax/delpos
        Route::post('/ajax/delpos',['uses'=>'Ajax\AdvanceController@deletePosition','as'=>'delAdvancePos']);
        //advances/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\AdvanceController@edit','as'=>'editAdvance']);
        //advances/edit
        Route::get('/edit/{id}',['uses'=>'AdvanceController@edit','as'=>'advanceEdit']);
    });

    //nomenclatures/ группа обработки роутов nomenclatures
    Route::group(['prefix'=>'nomenclatures'], function(){
        Route::get('/',['uses'=>'References\NomenclatureController@index','as'=>'nomenclatures']);
        Route::get('/view/{id}',['uses'=>'References\NomenclatureController@view','as'=>'nomenclatureView']);
        //nomenclature/addgroup
        Route::match(['get','post'],'/addgroup',['uses'=>'Ajax\NomenclatureController@createGroup','as'=>'groupNomenclatureAdd']);
        //nomenclature/add
        Route::match(['get','post'],'/add',['uses'=>'Ajax\NomenclatureController@create','as'=>'nomenclatureAdd']);
        //nomenclature/ajax/delete
        Route::post('/ajax/delete',['uses'=>'Ajax\NomenclatureController@delete','as'=>'deleteNomenclature']);
    });

    //sales/ группа обработки роутов sales
    Route::group(['prefix'=>'sales'], function(){
        Route::match(['get','post'],'/',['uses'=>'SaleController@index','as'=>'sales']);
        Route::get('/view/{id}',['uses'=>'SaleController@view','as'=>'saleView']);
        //sales/add
        Route::get('/add',['uses'=>'SaleController@create','as'=>'saleAdd']);
        //sales/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\SaleController@create','as'=>'addSale']);
        //sales/ajax/addpos
        Route::post('/ajax/addpos',['uses'=>'Ajax\SaleController@addPosition','as'=>'addSalePos']);
        //sales/ajax/delete
        Route::post('/ajax/delete',['uses'=>'Ajax\SaleController@delete','as'=>'delSale']);
        //sales/ajax/delpos
        Route::post('/ajax/delpos',['uses'=>'Ajax\SaleController@deletePosition','as'=>'delSalePos']);
        //sales/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\SaleController@edit','as'=>'editSale']);
        //sales/edit
        Route::get('/edit/{id}',['uses'=>'SaleController@edit','as'=>'saleEdit']);
    });

    //purchases/ группа обработки роутов purchases
    Route::group(['prefix'=>'purchases'], function(){
        Route::match(['get','post'],'/',['uses'=>'PurchaseController@index','as'=>'purchases']);
        Route::get('/view/{id}',['uses'=>'PurchaseController@view','as'=>'purchaseView']);
        //purchases/add
        Route::get('/add',['uses'=>'PurchaseController@create','as'=>'purchaseAdd']);
        //purchases/ajax/add
        Route::post('/ajax/add',['uses'=>'Ajax\PurchaseController@create','as'=>'addPurchase']);
        //purchases/ajax/addpos
        Route::post('/ajax/addpos',['uses'=>'Ajax\PurchaseController@addPosition','as'=>'addPurchasePos']);
        //purchases/ajax/delete
        Route::post('/ajax/delete',['uses'=>'Ajax\PurchaseController@delete','as'=>'delPurchase']);
        //purchases/ajax/delpos
        Route::post('/ajax/delpos',['uses'=>'Ajax\PurchaseController@deletePosition','as'=>'delPurchasePos']);
        //purchases/ajax/edit
        Route::post('/ajax/edit',['uses'=>'Ajax\PurchaseController@edit','as'=>'editPurchase']);
        //purchases/edit
        Route::get('/edit/{id}',['uses'=>'PurchaseController@edit','as'=>'purchaseEdit']);
    });

    //eventlog/ группа обработки роутов eventlog
    Route::group(['prefix'=>'eventlog'], function(){
        Route::get('/',['uses'=>'EventlogController@index','as'=>'eventlog']);
        //eventlog/edit
        Route::match(['get','post','delete'],'/edit/{id}',['uses'=>'EventlogController@edit','as'=>'eventEdit']);
    });
});

Auth::routes();