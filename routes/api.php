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
Route::get('/', ['name' => 'Point Of Sale (Web base Software).', 'version' => '1.1.0']);

/**
 * Authetication Routes.
 */
Route::prefix('authentication')->namespace('authentication')->group(function() {
    Route::post('register', 'AuthController@Register')->middleware(['api.guest']);
    Route::post('login', 'AuthController@Login')->middleware(['api.guest']);
    Route::post('me', 'AuthController@Me')->middleware(['api.user']);
    Route::post('refresh', 'AuthController@Refresh')->middleware(['api.user']);
    Route::post('logout', 'AuthController@Logout')->middleware(['api.user']);
});

/**
 * Customers Routes.
 */
Route::prefix('customers')->namespace('customers')->group(function() {
    Route::get('', 'CustomerController@Index');
    Route::post('create', 'CustomerController@Store')->middleware(['api.employee']);
    Route::patch('{customer}/update', 'CustomerController@Update');
    Route::delete('{customer}/delete', 'CustomerController@Destroy');
    Route::get('{customer}', 'CustomerController@Index');
});

/**
 * Users Routes.
 */
Route::prefix('user')->namespace('user')->group(function() {
    Route::patch('update', 'UserController@Update')->middleware(['api.user']);
    Route::patch('upload', 'ProfilePictureController@Upload')->middleware(['api.user']);
    Route::patch('change/password', 'ChangePasswordController@Change')->middleware(['api.user']);
    Route::post('{user}/add/role', 'RoleController@Add')->middleware(['api.administrator']);
});

/**
 * Products Route
 */
Route::prefix('products')->namespace('products')->group(function() {
    Route::post('create', 'ProductController@Store')->middleware(['api.administrator']);
    Route::prefix('categories')->group(function() {
        Route::post('create', 'CategoryController@Store')->middleware(['api.administrator']);
        Route::prefix('sub')->group(function() {
            Route::post('{category}/create', 'SubCategoryController@Store')->middleware(['api.administrator']);
        });
    });
});

/**
 * Service Route
 */
Route::prefix('services')->namespace('Services')->group(function() {
    Route::prefix('categories')->group(function() {
        Route::post('create', 'CategoryController@Store')->middleware(['api.administrator']);
        Route::prefix('sub')->group(function() {
            Route::post('{category}/create', 'SubCategoryController@Store')->middleware(['api.administrator']);
        });
    });
});

/**
 * Transactions
 */
Route::prefix('transactions')->namespace('transactions')->group(function() {
    Route::post('checkout', 'TransactionController@Store')->middleware(['api.employee']);
});
