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

Route::get('/bios', 'BiosController@index')->name('bios.index');
Route::post('/queen-game', 'QueenGameController@save')->name('queen.game.save');

$tenantParam = config('tenant.route_param');

Route::get('/', 'CurriculumController@curriculum')->name('curriculum');
Route::post('/contact', 'CurriculumController@contact')->name('contact');
Route::prefix('/tribal-wars')->name('tribal.')->group(function(){
    Route::get('', 'TribalWarsController@index')->name('index');
    Route::get('collect', 'TribalWarsController@collect')->name('collect');
});


Route::domain(config('app.url'))->group(function() use($tenantParam){
Route::prefix("{{$tenantParam}}")
    ->middleware('tenant')
    ->group(function() {
        Route::get('/', 'WelcomeController@index')->name('welcome');

        Auth::routes([
            'register' => false,
            'reset' => false,
            'verify' => false,
        ]);

        Route::get('/home', 'HomeController@index')->name('home');

        Route::middleware('auth:web_tenant')->group(function () {
            //=========================================FIPE===========================================================//
            Route::get('fipe/models/{id}', 'FipeController@models')->name('fipe.models');
            Route::get('fipe/years/{brand_id}/{model_id}', 'FipeController@years')->name('fipe.years');
            Route::get('fipe/price/{brand_id}/{model_id}/{year_id}', 'FipeController@price')->name('fipe.price');
            //=========================================BANK ACCOUNT===========================================================//
            Route::get('/bank_account/get', 'BankAccountController@get')->name('bank_account.get');
            Route::get('/bank_account/expense/report', 'BankAccountController@reportExpense')->name('bank_account.expense.report');
            //=========================================Supplier===========================================================//
            Route::get('/supplier/get', 'SupplierController@get')->name('supplier.get');
            //=========================================Supplier===========================================================//
            Route::get('/brand/get', 'BrandController@get')->name('brand.get');
            //=========================================Product===========================================================//
            Route::get('/product/get', 'ProductController@get')->name('product.get');
            //=========================================Product Supplier===========================================================//
            Route::get('/product_supplier/get/{product_id}', 'ProductSupplierController@get')->name('product_supplier.get');
            Route::get('/product/supplier', 'ProductSupplierController@index')->name('product.supplier.index');
            Route::get('/product/{id}/supplier/create', 'ProductSupplierController@create')->name('product.supplier.create');
            Route::post('/product/{id}/supplier/store', 'ProductSupplierController@store')->name('product.supplier.store');
            //=========================================Invoice===========================================================//
            Route::get('/invoice/get', 'InvoiceController@get')->name('invoice.get');
            Route::get('/invoice/create/qr_code', 'InvoiceController@createByQrCode')->name('invoice.create.qr_code');
            Route::post('/invoice/qr_code', 'InvoiceController@storeByQrCode')->name('invoice.store.qr_code');
            //=========================================Invoice Product===========================================================//
            Route::put('/invoice_product/{id}', 'InvoiceProductController@update')->name('invoice_product.update');
            Route::get('/invoice_product/{invoice_product}/create/product', 'InvoiceProductController@createProductByInvoiceProduct')->name('invoice_product.create.product');
            //=========================================BANK ACCOUNT POSTING===================================================//
            Route::get('/bank_account_posting/{id}', 'BankAccountPostingController@indexPostingByBank')->name('bank_account_posting.index');
            Route::get('/bank_account_posting/show/{id}', 'BankAccountPostingController@show')->name('bank_account_posting.show');
            Route::get('/bank_account_posting/get/{id}', 'BankAccountPostingController@get')->name('bank_account_posting.get');
            Route::get('/bank_account_posting/', 'BankAccountPostingController@file')->name('bank_account_posting.file');
            Route::post('/bank_account_posting/read_file', 'BankAccountPostingController@readFileStore')->name('bank_account_posting.read_file');
            //=========================================INCOME=================================================================//
            Route::get('/income/get', 'IncomeController@get')->name('income.get');
            //=========================================EXPENSE=================================================================//
            Route::get('/expense/get', 'ExpensesController@get')->name('expense.get');
            //=========================================BUDGET FINANCIAL=================================================================//
            Route::post('/budget_financial/updateinitialbalance/{id}', 'BudgetFinancialController@updateInitialBalance')->name('budget_financial.updateinitialbalance');
            Route::get('/budget_financial/last_month/{id}', 'BudgetFinancialController@lastMonth')->name('budget_financial.last_month');
            Route::get('/budget_financial/restart/{id}', 'BudgetFinancialController@budgetFinancialMonthByBankAccount')->name('budget_financial.restart');
            Route::get('/budget_financial/generate_fixed/{id}', 'BudgetFinancialController@generateFixed')->name('budget_financial.generate_fixed');
            //=========================================BUDGET FINANCIAL POSTING=================================================================//
            Route::get('/budget_financial_posting/get/{id}', 'BudgetFinancialPostingController@get')->name('budget_financial_posting.get');
            //=========================================CAR=================================================================//
            Route::prefix('car')->name('car.')->group(function(){
                Route::get('/',             'CarController@index')       ->name('index');
                Route::get('/profile/{id}', 'CarController@profile')     ->name('profile');
                Route::post('/',            'CarController@store')       ->name('store');
                Route::get('/create',       'CarController@create')      ->name('create');
                Route::get('/get',          'CarController@get')         ->name('get');
                Route::get('/{id}',         'CarController@show')        ->name('show');
                Route::put('/{id}',         'CarController@update')      ->name('update');
                Route::delete('/{id}',      'CarController@destroy')     ->name('destroy');
                Route::get('/{id}/edit',    'CarController@edit')        ->name('edit');
            });
            //=========================================CREDCARD=================================================================//
        Route::prefix('cred_card')->name('cred_card.')->group(function(){
            Route::get('/',             'CredCardController@index')       ->name('index');
            Route::post('/',            'CredCardController@store')       ->name('store');
            Route::get('/create',       'CredCardController@create')      ->name('create');
            Route::get('/get',          'CredCardController@get')         ->name('get');
            Route::get('/{id}',         'CredCardController@show')        ->name('show');
            Route::put('/{id}',         'CredCardController@update')      ->name('update');
            Route::delete('/{id}',      'CredCardController@destroy')     ->name('destroy');
            Route::get('/{id}/edit',    'CredCardController@edit')        ->name('edit');
        });
        //=========================================CARSUPPLIES=================================================================//
        Route::prefix('car_supply')->name('car_supply.')->group(function(){
            Route::get('/{car_id}',     'CarSupplyController@index')       ->name('index');
            Route::post('/',            'CarSupplyController@store')       ->name('store');
            Route::get('/create/{car_id}','CarSupplyController@create')    ->name('create');
            Route::get('/get/{car_id}', 'CarSupplyController@get')         ->name('get');
            Route::get('/{id}',         'CarSupplyController@show')        ->name('show');
            Route::put('/{id}',         'CarSupplyController@update')      ->name('update');
            Route::delete('/{id}',      'CarSupplyController@destroy')     ->name('destroy');
            Route::get('/{id}/edit',    'CarSupplyController@edit')        ->name('edit');
        });
        Route::apiResource(
            'bank_account_posting',
            'BankAccountPostingController'
        )->except(['index', 'show']);
            Route::resources([
                'bank_accounts'             => 'BankAccountController',
                'bank'                      => 'BankController',
                'budget_financial'          => 'BudgetFinancialController',
                'budget_financial_posting'  => 'BudgetFinancialPostingController',
                'income'                    => 'IncomeController',
                'expense'                   => 'ExpensesController',
                'supplier'                  => 'SupplierController',
                'product'                   => 'ProductController',
                'product_supplier'          => 'ProductSupplierController',
                'invoice'                   => 'InvoiceController',
                'brand'                     => 'BrandController',
            ]);
        });
    });
});
