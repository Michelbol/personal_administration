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
$tenantParam = config('tenant.route_param');


Route::domain(config('app.url'))->group(function() use($tenantParam){
Route::prefix("{{$tenantParam}}")
    ->middleware('tenant')
    ->group(function() {
        Route::get('/', function () {
            return view('welcome');
        });

        Auth::routes();

        Route::get('/home', 'HomeController@index')->name('home');

        Route::middleware('auth:web_tenant')->group(function () {
            //=========================================BANK ACCOUNT===========================================================//
            Route::get('/bank_account/get', 'BankAccountController@get')->name('bank_account.get');
            //=========================================BANK ACCOUNT POSTING===================================================//
            Route::get('/bank_account_posting/{id}', 'BankAccountPostingController@index')->name('bank_account_posting.index');
            Route::get('/bank_account_posting/show/{id}', 'BankAccountPostingController@show')->name('bank_account_posting.show');
            Route::get('/bank_account_posting/get/{id}', 'BankAccountPostingController@get')->name('bank_account_posting.get');
            Route::get('/bank_account_posting/', 'BankAccountPostingController@file')->name('bank_account_posting.file');
            Route::post('/bank_account_posting/read_file', 'BankAccountPostingController@readFileStore')->name('bank_account_posting.read_file');
            Route::post('/bank_account_posting', 'BankAccountPostingController@store')->name('bank_account_posting.store');
            //=========================================INCOME=================================================================//
            Route::get('/income/get', 'IncomeController@get')->name('income.get');
            //=========================================EXPENSE=================================================================//
            Route::get('/expense/get', 'ExpensesController@get')->name('expense.get');
            //=========================================BUDGET FINANCIAL=================================================================//
            Route::post('/budget_financial/updateinitialbalance/{id}', 'BudgetFinancialController@updateInitialBalance')->name('budget_financial.updateinitialbalance');
            Route::get('/budget_financial/last_month/{id}', 'BudgetFinancialController@lastMonth')->name('budget_financial.last_month');
            //=========================================BUDGET FINANCIAL POSTING=================================================================//
            Route::get('/budget_financial_posting/get/{id}', 'BudgetFinancialPostingController@get')->name('budget_financial_posting.get');
        //=========================================CREDCARD=================================================================//
        Route::prefix('cred_card')->name('cred_card.')->group(function(){
            $controller = 'CredCardController';
            Route::get('/',             $controller.'@index')       ->name('index');
            Route::post('/',            $controller.'@store')       ->name('store');
            Route::get('/create',       $controller.'@create')      ->name('create');
            Route::get('/get',          $controller.'@get')         ->name('get');
            Route::get('/{id}',         $controller.'@show')        ->name('show');
            Route::put('/{id}',         $controller.'@update')      ->name('update');
            Route::delete('/{id}',      $controller.'@destroy')     ->name('destroy');
            Route::get('/{id}/edit',    $controller.'@edit')        ->name('edit');
        });
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
