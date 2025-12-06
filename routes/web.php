<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\TransactionApiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\web\AccountController;
use App\Http\Controllers\web\CategoryController;
use App\Http\Controllers\web\TransactionController;
use App\Http\Controllers\web\HealthController;
use App\Http\Controllers\Api\AccountApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/profile', [ProfileController::class, 'creaye'])->name('profile.edit');
Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->name('register');


Route::middleware(['auth', 'throttle:global'])->group(function () {
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.create');
    Route::delete('/accounts/{account}', [AccountController::class, 'delete'])->name('accounts.delete');
    Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');

    Route::post('/transactions', [TransactionController::class, 'create'])->name('transactions.create');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    Route::get('/categories/{type}', [CategoryController::class, 'index'])->name('categories.index');

});


Route::prefix('api')->middleware('throttle:global')->group(function () {
    Route::post('/accounts', [AccountApiController::class, 'store'])->middleware('idempotent');
    Route::get('/accounts', [AccountApiController::class, 'index']);
    Route::put('/accounts/{id}', [AccountApiController::class, 'update']);
    Route::delete('/accounts/{id}', [AccountApiController::class, 'destroy']);

    Route::get('/categories/{type}', [CategoryApiController::class, 'index']);

    Route::post('/transactions', [TransactionApiController::class, 'store'])->middleware('idempotent');
    Route::get('/transactions', [TransactionApiController::class, 'index']);
    Route::put('/transactions/{id}', [TransactionApiController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionApiController::class, 'destroy']);

});



Route::get('/health', [HealthController::class, 'check']);
Route::view('/test', 'test');
Route::post('/test-backoff', TestController::class);
