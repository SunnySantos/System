<?php

use App\Console\Commands\DeactivateInactiveUsers;
use App\Http\Middleware\CheckRoleAccess;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustHosts(at: ['localhost:8000']);

        $middleware->redirectGuestsTo('/login');

        $middleware->redirectUsersTo('/dashboard');

        $middleware->appendToGroup('auth', [
            CheckRoleAccess::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Schedule::command('auth:clear-resets')->everyFifteenMinutes();
        $schedule->command('users:deactivate-inactive')->daily();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
