<?php

use App\Http\Controllers\Api\CourierController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [UserController::class, 'login']);
Route::post('/logincustomer', [CustomerController::class, 'logincustomer']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/registercustomer', [CustomerController::class, 'registercustomer']);
Route::get('/store', [StoreController::class, 'list']);
Route::get('/product-category', [ProductCategoryController::class, 'list']);
Route::get('/courier', [CourierController::class, 'list']);
Route::get('/product', [ProductController::class, 'list']);
Route::get('/product/{id}', [ProductController::class, 'listdetail']);
Route::get('/productbycategory/{id}', [ProductController::class, 'listbycategory']);
Route::get('/productlimit', [ProductController::class, 'listlimit']);
Route::get('/productrating/{rating}/{id}', [ProductController::class, 'updaterating']);
Route::get('/product-latest', [ProductController::class, 'latest']);
Route::post('/checkout', [TransactionController::class, 'save']);
Route::get('/checkout/{id}', [TransactionController::class, 'history']);
Route::get('/checkout/{id}/{status}', [TransactionController::class, 'historybystatus']);
Route::post('/cancel/{id}', [TransactionController::class, 'cancel']);
Route::post('/upload', [TransactionController::class, 'uploadbukti']);
