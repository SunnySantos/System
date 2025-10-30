<?php

namespace App\Listeners;

use App\Models\Audit;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class LogUserAuthActivity
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if ($event instanceof Login) {
            Audit::create([
                'user_id'           => $event->user->id,
                'event'             => 'login',
                'auditable_type'    => get_class($event->user),
                'auditable_id'      => $event->user->id,
                'new_values'        => [
                    'email'         => $event->user->email ?? null,
                ],
                'message'           => 'User logged in',
                'ip_address'        => Request::ip(),
            ]);
        }

        if ($event instanceof Logout) {
            Audit::create([
                'user_id'           => $event->user->id,
                'event'             => 'logout',
                'auditable_type'    => get_class($event->user),
                'auditable_id'      => $event->user->id,
                'new_values'        => [
                    'email'         => $event->user->email ?? null,
                ],
                'message'           => 'User logged out',
                'ip_address'        => Request::ip(),
            ]);
        }

        if ($event instanceof Failed) {
            Audit::create([
                'user_id'           => null,
                'event'             => 'failed_login',
                'auditable_type'    => 'App\Models\User',
                'auditable_id'      => 0,
                'new_values'        => [
                    'email'         => $event->credentials['email'] ?? null,
                ],
                'message'           => 'Failed login attempt',
                'ip_address'        => Request::ip(),
            ]);
        }
    }
}
