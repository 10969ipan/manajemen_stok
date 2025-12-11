<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/', function () {
    return view('auth.login');
})->middleware('guest')->name('home
');
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Only Routes
    Route::middleware('admin')->group(function () {
        // User Management
        Route::resource('users', UserController::class)->except(['show']);

        // Categories
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Units
        Route::resource('units', UnitController::class)->except(['show']);

        // Items Management (Admin Only)
        Route::resource('items', ItemController::class)->except(['index', 'show']);

        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
            Route::get('/stock/download', [ReportController::class, 'downloadStockReport'])->name('reports.stock.download');
            Route::get('/transactions', [ReportController::class, 'transactionReport'])->name('reports.transactions');
            Route::get('/transactions/download', [ReportController::class, 'downloadTransactionReport'])->name('reports.transactions.download');
            Route::get('/requests', [ReportController::class, 'requestReport'])->name('reports.requests');
            Route::get('/requests/download', [ReportController::class, 'downloadRequestReport'])->name('reports.requests.download');
        });

        Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store']);
    });


    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

    // Item Requests
    Route::resource('item-requests', ItemRequestController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/item-requests/{item_request}/approve', [ItemRequestController::class, 'approve'])->name('item-requests.approve');
    Route::post('/item-requests/{item_request}/reject', [ItemRequestController::class, 'reject'])->name('item-requests.reject');
});