<?php

use App\Http\Controllers\ConfirmPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login.index');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::delete('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::resource('users', UserController::class);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/settings', [SettingsController::class, 'index'])->middleware(['password.confirm'])->name('settings.index');
    
    Route::get('/confirm-password', [ConfirmPasswordController::class, 'index'])->middleware(['auth'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmPasswordController::class, 'confirm'])->middleware(['throttle:6,1']);
});
