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

Route::domain(config('app.url'))->group(function(){
    Route::get('/', function () {
        return view('welcome');
    });

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');

    Route::middleware('auth')->group(function () {
        //=========================================BANK ACCOUNT===========================================================//
        Route::get('/bank_account/get', 'BankAccountController@get')->name('bank_account.get');
        //=========================================BANK ACCOUNT POSTING===================================================//
        Route::get('/bank_account_posting/{id}', 'BankAccountPostingController@index')->name('bank_account_posting.index');
        Route::get('/bank_account_posting/get/{id}', 'BankAccountPostingController@get')->name('bank_account_posting.get');
        Route::get('/bank_account_posting/', 'BankAccountPostingController@file')->name('bank_account_posting.file');
        Route::post('/bank_account_posting/read_file', 'BankAccountPostingController@readFileStore')->name('bank_account_posting.read_file');
        Route::post('/bank_account_posting', 'BankAccountPostingController@store')->name('bank_account_posting.store');
        //=========================================INCOME=================================================================//
        Route::get('/income/get', 'IncomeController@get')->name('income.get');
        //=========================================EXPENSE=================================================================//
        Route::get('/expense/get', 'ExpensesController@get')->name('expense.get');
        //=========================================BUDGET FINANCIAL POSTING=================================================================//
        Route::get('/budget_financial_posting/get/{id}', 'BudgetFinancialPostingController@get')->name('budget_financial_posting.get');


        Route::resources([
            'bank_accounts'     => 'BankAccountController',
            'bank'              => 'BankController',
            'budget_financial'  => 'BudgetFinancialController',
            'income'            => 'IncomeController',
            'expense'           => 'ExpensesController'
        ]);
    });
});
