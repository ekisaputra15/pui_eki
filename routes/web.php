<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\QRController;

// Public routes & Customer Routes
Route::get('/', [CustomerController::class, 'index'])->name('home');
Route::get('/scan/{table_id}', [CustomerController::class, 'scan'])->name('customer.scan');
Route::get('/menu', [CustomerController::class, 'menu'])->name('customer.menu');
Route::get('/cart', [CustomerController::class, 'cart'])->name('customer.cart');
Route::post('/cart/update', [CustomerController::class, 'updateCart'])->name('customer.cart.update');
Route::post('/checkout', [CustomerController::class, 'checkout'])->name('customer.checkout');
Route::get('/status', [CustomerController::class, 'status'])->name('customer.status');
Route::post('/order/cancel', [CustomerController::class, 'cancelOrder'])->name('customer.order.cancel');
Route::get('/no_table', [CustomerController::class, 'noTable'])->name('customer.notable');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Staff Dashboard Routes
Route::middleware(['auth'])->group(function () {
    
    // Fallback dashboard redirect based on role
    Route::get('/dashboard', [AuthController::class, 'dashboardRedirect'])->name('dashboard');

    // Kasir
    Route::middleware(['role:kasir'])->prefix('staff/cashier')->name('cashier.')->group(function () {
        Route::get('/', [StaffController::class, 'cashierIndex'])->name('index');
        Route::get('/tables', [StaffController::class, 'cashierTables'])->name('tables');
        Route::post('/tables/store', [StaffController::class, 'cashierTableStore'])->name('tables.store');
        Route::get('/tables/{id}', [StaffController::class, 'tableDetail'])->name('table_detail');
        Route::post('/confirm', [StaffController::class, 'cashierConfirm'])->name('confirm');
    });

    // Dapur
    Route::middleware(['role:dapur'])->prefix('staff/kitchen')->name('kitchen.')->group(function () {
        Route::get('/', [StaffController::class, 'kitchenIndex'])->name('index');
        Route::post('/action', [StaffController::class, 'kitchenAction'])->name('action');
    });

    // Waitress
    Route::middleware(['role:waitress'])->prefix('staff/waitress')->name('waitress.')->group(function () {
        Route::get('/', [StaffController::class, 'waitressIndex'])->name('index');
        Route::get('/history', [StaffController::class, 'waitressHistory'])->name('history');
        Route::post('/clear', [StaffController::class, 'clearTable'])->name('clear');
    });

    // Share QR Generation (Kasir & Admin)
    Route::middleware(['role:kasir,pengelola'])->group(function() {
        Route::get('/admin/tables/qr/{id}', [QRController::class, 'generateSinglePDF'])->name('admin.tables.qr.single');
        Route::get('/admin/tables/qr-all', [QRController::class, 'generateAllPDF'])->name('admin.tables.qr.all');
    });

    // Pengelola (Admin)
    Route::middleware(['role:pengelola'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/orders/{id}', [AdminController::class, 'orderDetail'])->name('orders.detail');
        
        // Products
        Route::get('/products', [AdminController::class, 'productIndex'])->name('products.index');
        Route::post('/products', [AdminController::class, 'productStore'])->name('products.store');
        Route::put('/products/{id}', [AdminController::class, 'productUpdate'])->name('products.update');
        Route::delete('/products/{id}', [AdminController::class, 'productDestroy'])->name('products.destroy');
        
        // Users
        Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
        Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
        Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');

        // Admin Table Monitor (Shared route for view only)
        Route::get('/tables', [AdminController::class, 'tableIndex'])->name('tables.index');
    });
});