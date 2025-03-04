<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refreshToken']);
    });
});
// Route::apiResource('items',ItemController::class);
// Routes for ItemController
Route::get('/items', [ItemController::class, 'listItems']);           
Route::post('/items', [ItemController::class, 'createNewItem']);     
Route::get('/items/{id}', [ItemController::class, 'showSingleItem']); 
Route::put('/items/{id}', [ItemController::class, 'updateItem']);     
Route::delete('/items/{id}', [ItemController::class, 'deleteItem']);

Route::get('/orders', [OrderController::class, 'listOrders']);
Route::post('/orders', [OrderController::class, 'createOrder']);
Route::get('/orders/{id}', [OrderController::class, 'singleOrder']);
Route::put('/orders/{id}', [OrderController::class, 'updateOrder']);
Route::delete('/orders/{id}', [OrderController::class, 'deleteOrder']);
Route::get('/orders/user/{userId}', [OrderController::class, 'getOrdersByUserId']);