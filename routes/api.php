<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KasirApiController;
use App\Http\Controllers\Api\KitchenApiController;
use App\Http\Controllers\Api\TableApiController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);

    // Kasir API
    Route::get('/kasir/orders', [KasirApiController::class, 'index']);
    Route::post('/kasir/orders/{id}/confirm', [KasirApiController::class, 'confirm']);
    Route::get('/kasir/tables', [KasirApiController::class, 'tables']);

    // Dapur API
    Route::get('/kitchen/orders', [KitchenApiController::class, 'index']);
    Route::post('/kitchen/orders/{id}/action', [KitchenApiController::class, 'action']);

    // Waitress API
    Route::get('/tables', [TableApiController::class, 'index']);
    Route::post('/waitress/tables/clear', [TableApiController::class, 'clearTable']);
    Route::get('/waitress/history', [TableApiController::class, 'history']);

    // Pengelola API
    Route::get('/pengelola/stats', [\App\Http\Controllers\Api\PengelolaApiController::class, 'stats']);
    
    // Products
    Route::get('/pengelola/products', [\App\Http\Controllers\Api\PengelolaApiController::class, 'products']);
    Route::post('/pengelola/products', [\App\Http\Controllers\Api\PengelolaApiController::class, 'storeProduct']);
    Route::post('/pengelola/products/{id}', [\App\Http\Controllers\Api\PengelolaApiController::class, 'updateProduct']);
    Route::delete('/pengelola/products/{id}', [\App\Http\Controllers\Api\PengelolaApiController::class, 'destroyProduct']);
    
    // Tables
    Route::get('/pengelola/tables', [\App\Http\Controllers\Api\PengelolaApiController::class, 'tables']);
    Route::post('/pengelola/tables', [\App\Http\Controllers\Api\PengelolaApiController::class, 'storeTable']);
    Route::delete('/pengelola/tables/{id}', [\App\Http\Controllers\Api\PengelolaApiController::class, 'destroyTable']);
    
    // Staff
    Route::get('/pengelola/staff', [\App\Http\Controllers\Api\PengelolaApiController::class, 'staff']);
    Route::post('/pengelola/staff', [\App\Http\Controllers\Api\PengelolaApiController::class, 'storeStaff']);
    Route::post('/pengelola/staff/{id}', [\App\Http\Controllers\Api\PengelolaApiController::class, 'updateStaff']);
    Route::delete('/pengelola/staff/{id}', [\App\Http\Controllers\Api\PengelolaApiController::class, 'destroyStaff']);
});
