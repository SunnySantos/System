<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::check()) {
            return redirect()->route('login.index');
        }

        $user = Auth::user();
        $currentRouteName = $request->route()->getName();

        // Restrict non–Super Admin users from accessing Super Admin roles.
        if ($request->route('role')) {
            $roleId = $request->route('role')->id;

            if ($user->role_id != 1 && $roleId == 1) {
                return redirect()->route('roles.index');
            }
        }


        $excludedRoutes = [
            'users.edit'
        ];

        if (in_array($currentRouteName, $excludedRoutes)) {
            return $next($request);
        }

        if (!$user->canAccess($currentRouteName)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
