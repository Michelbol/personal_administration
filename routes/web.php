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

$tenantParam = config('tenant.route_param');


Route::domain(config('app.url'))->group(function() use($tenantParam){
Route::prefix("{{$tenantParam}}")
    ->middleware('tenant')
    ->group(function() {
        Route::get('/', 'WelcomeController@index')->name('welcome');

        Auth::routes();

        Route::get('/home', 'HomeController@index')->name('home');

        Route::middleware('auth:web_tenant')->group(function () {
            //=========================================BANK ACCOUNT===========================================================//
            Route::get('/bank_account/get', 'BankAccountController@get')->name('bank_account.get');
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
            //=========================================BUDGET FINANCIAL POSTING=================================================================//
            Route::get('/budget_financial_posting/get/{id}', 'BudgetFinancialPostingController@get')->name('budget_financial_posting.get');
            //=========================================CAR=================================================================//
            Route::prefix('car')->name('cars.')->group(function(){
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
            ]);
        });
    });
});
