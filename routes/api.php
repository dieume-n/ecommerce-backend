<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Auth\SigninController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Categories\CategoryController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/categories', CategoryController::class);
Route::resource('/products', ProductController::class);
Route::resource('/addresses', AddressController::class);
Route::get('/countries', [CountryController::class, 'index']);

Route::group(['prefix' => 'auth'], function () {
    Route::post('signup', [SignupController::class, 'signup']);
    Route::post('signin', [SigninController::class, 'signin']);
    Route::get('me', [MeController::class, 'me']);
});

Route::resource('/cart', CartController::class, [
    'parameters' => [
        'cart' => 'productVariation'
    ]
]);
