<?php

namespace App\Providers;

use App\Listeners\LogUserAuthActivity;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Vite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiter();

        Vite::macro('image', fn($asset) => Vite::asset("resources/images/{$asset}"));

        // For Audit Trail
        Event::listen(
            [
                Login::class,
                Logout::class,
                Failed::class,
            ],
            LogUserAuthActivity::class,
        );
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiter(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
