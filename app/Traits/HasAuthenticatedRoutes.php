<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

trait HasAuthenticatedRoutes
{
    /**
     * Retrieve all route names protected by the 'auth' middleware.
     *
     * @param  array|null  $modules  Optional list of module prefixes to limit the result
     * @param  int  $ttl  Cache duration in seconds (default: 1 hour)
     * @return array
     */
    public static function getAuthenticatedRoutes(?array $modules = null, int $ttl = 3600): array
    {
        // Use provided modules or default Role::MODULES
        $modules = $modules ?? Role::MODULES;

        // Create a unique cache key based on modules list
        $cacheKey = 'authenticated_routes_' . md5(json_encode($modules));

        return Cache::remember($cacheKey, $ttl, function () use ($modules) {
            return collect(Route::getRoutes())
                ->filter(fn($route) => in_array('auth', $route->gatherMiddleware()))
                ->map(function ($route) use ($modules) {
                    $name = $route->getName();

                    if (!$name) {
                        return null; // Skip unnamed routes
                    }

                    $parts = explode('.', $name, 2);
                    $module = $parts[0] ?? null;
                    $action = $parts[1] ?? null;

                    // Validate module and action
                    if (!in_array($module, $modules, true)) {
                        return null;
                    }

                    // if (!array_key_exists($action, Role::ROUTE_ACTIONS)) {
                    //     return null;
                    // }

                    return $name;
                })
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->all();
        });
    }
}
