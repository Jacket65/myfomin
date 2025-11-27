<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\TransactionApiController;
use App\Http\Controllers\web\AccountController;
use App\Http\Controllers\web\CategoryController;
use App\Http\Controllers\web\TransactionController;
use App\Http\Controllers\web\HealthController;
use App\Http\Controllers\Api\AccountApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return view('loadapp');
});

Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.create');
Route::delete('/accounts/{id}', [AccountController::class, 'delete'])->name('accounts.delete');
Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');

Route::post('/transactions', [TransactionController::class, 'create'])->name('transactions.create');
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

Route::get('/categories/{type}', [CategoryController::class, 'index'])->name('categories.index');

Route::get('/test', function () {
    return view('test');
});

Route::prefix('api')->group(function () {
    Route::post('/accounts', [AccountApiController::class, 'store']);
    Route::get('/accounts', [AccountApiController::class, 'index']);
    Route::put('/accounts/{id}', [AccountApiController::class, 'update']);
    Route::delete('/accounts/{id}', [AccountApiController::class, 'destroy']);

    Route::get('/categories/{type}', [CategoryApiController::class, 'index']);

    Route::post('/transactions', [TransactionApiController::class, 'store']);
    Route::get('/transactions', [TransactionApiController::class, 'index']);
    Route::put('/transactions/{id}', [TransactionApiController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionApiController::class, 'destroy']);

});

Route::get('/health', [HealthController::class, 'check']);