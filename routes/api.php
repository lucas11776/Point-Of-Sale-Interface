<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/**
 * Api Home Request.
 */
Route::get('/', function() {
    return response()->json(['name' => 'Point Of Sale', 'version' => '1.1.0']);
});

/**
 * Authetication Routes.
 */
Route::prefix('authentication')->namespace('authentication')->group(function() {
    Route::post('register', 'AuthController@Register')->middleware(['api.guest']);
    Route::post('login', 'AuthController@Login')->middleware(['api.guest']);
    Route::post('refresh', 'AuthController@Refresh')->middleware(['api.user']);
    Route::post('logout', 'AuthController@Logout')->middleware(['api.user']);
});

/**
 * Customers Routes
 */
Route::prefix('customers')->namespace('customers')->group(function() {
    Route::post('create', 'CustomerController@Store')->middleware(['api.user', 'api.employee']);
    Route::patch('update/{customer}', 'CustomerController@Update');
    Route::delete('delete/{customer}', 'CustomerController@Destroy');
    Route::get('', 'CustomerController@Index');
    Route::get('{customer}', 'CustomerController@Index');
});

/**
 * RegisterRequest Routes.
 */

/**
 * Users Routes.
 */

/**
 * Transactions
 */
