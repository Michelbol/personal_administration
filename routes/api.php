<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
$tenantParam = config('tenant.route_param');

Route::domain(config('app.url'))->group(function() use($tenantParam){
    Route::prefix("{{$tenantParam}}")
        ->middleware('tenant')
        ->namespace('Api\\')
        ->group(function() {
            Route::post('invoice/qr_code', 'InvoiceController@storeByQrCode');
        });
});
