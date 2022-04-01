<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);

    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{product}',  [ProductController::class, 'update']);
    Route::delete('products/{product}',  [ProductController::class, 'destroy']);

    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/{id}', [CustomerController::class, 'show']);
    Route::post('customers', [CustomerController::class, 'store']);
    Route::put('customers/{product}',  [CustomerController::class, 'update']);
    Route::delete('customers/{product}',  [CustomerController::class, 'destroy']);
    
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::put('orders/{product}',  [OrderController::class, 'update']);
    Route::delete('orders/{product}',  [OrderController::class, 'destroy']);
});