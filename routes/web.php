<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Route::get('/user', [UserController::class, 'index'])->name('user.index');
// Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::delete('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
Route::resource('users', UserController::class);
