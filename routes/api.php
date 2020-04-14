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

Route::get('/', function() {
    return response()->json(['name' => 'Point Of Sale', 'version' => '1.1.0']);
});

/**
 * User Authetication Routes.
 */
Route::prefix('authentication')->namespace('authentication')->group(function() {
    Route::post('register', 'AuthController@Register')->middleware(['api.guest']);
    Route::post('login', 'AuthController@Login')->middleware(['api.guest']);
    Route::post('refresh', 'AuthController@Refresh')->middleware(['api.user']);
    Route::post('logout', 'AuthController@Logout')->middleware(['api.user']);
});

/**
 * User Account Routes.
 */

/**
 * Users Accounts Routes.
 */

/**
 * Transactions
 */
