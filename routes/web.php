<?php

use App\Http\Controllers\ConfirmPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRoleAccess;
use App\Mail\TestMail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


// Route::middleware(['guest'])->group(function () {
//     Route::get('/login', [LoginController::class, 'index'])->name('login.index');
//     Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
// });

Auth::routes([
    'verify' => true,
    'register' => false,
    // 'reset' => false,
]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::delete('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::resource('users', UserController::class);
    Route::get('/settings', [SettingsController::class, 'index'])->middleware(['password.confirm'])->name('settings.index');

    Route::get('/confirm-password', [ConfirmPasswordController::class, 'index'])->name('password.confirm');

    Route::delete('/roles/bulk-delete', [RoleController::class, 'bulkDelete'])->name('roles.bulk-delete');
    Route::delete('/roles/update-user-role', [RoleController::class, 'updateUserRoleAfterDelete'])->name('roles.update-user-role');
    Route::resource('roles', RoleController::class);

    Route::withoutMiddleware([CheckRoleAccess::class])->group(function () {
        // Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::post('/confirm-password', [ConfirmPasswordController::class, 'confirm'])->middleware(['throttle:6,1']);
    });
});


// Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->middleware(['auth'])->name('verification.notice');
// Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');
// Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/send-test-email', function () {
    $data = ['message', 'Hello from Laravel!'];

    Mail::to('santos.sunny121@gmail.com')->send(new TestMail($data));

    return '✅ Email sent successfully!';
});
