<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\LaundryTypeController;
use App\Http\Controllers\OrderController;

// 公開 API
Route::post('/register', [AuthController::class, 'register']);
Route::get('/laundry-types', [LaundryTypeController::class, 'index']);

// 需要認證的 API
Route::middleware('auth:sanctum')->group(function () {
    // 用戶資訊
    Route::get('/user', [UserController::class, 'currentUser']);

    // 訂單 API (共用部分)
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    // 僅客戶可用 API
    Route::middleware('role:customer')->group(function () {
        Route::post('/orders', [OrderController::class, 'store']);
    });

    // 僅員工可用 API
    Route::middleware('role:employee')->group(function () {
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
        Route::get('/customers', [UserController::class, 'customers']);
    });
});
