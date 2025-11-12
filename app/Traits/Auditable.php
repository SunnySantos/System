<?php

namespace App\Traits;

use App\Models\Audit;
use App\Models\RoleAccess;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable(): void
    {
        Log::debug('BOOT AUDITABLE');

        $exludedClass = [
            UserProfile::class,
            RoleAccess::class,
        ];

        static::created(function ($model) use ($exludedClass) {
            // Log::debug('[CREATE]: ' . print_r($model, true));

            if (in_array(static::class, $exludedClass) === false) {
                $model->createAudit('created');
            }
        });

        static::updated(function ($model) {
            // Log::debug('[UPDATE]: ' . print_r($model, true));

            $hasExclusion = false;
            $changes = $model->changes;
            $exclude = ['last_login_at', 'remember_token'];

            foreach (array_keys($changes) as $value) {
                if (in_array($value, $exclude)) {
                    $hasExclusion = true;
                    break;
                }
            }

            if (!$hasExclusion) {
                $model->createAudit('updated');
            }
        });

        // static::saved(function ($model) {
        //     Log::debug('[SAVED]: ' . print_r($model, true));
        // });

        static::deleted(function ($model) use ($exludedClass) {
            // Log::debug('[DELETE]: ' . print_r($model, true));

            if (in_array(static::class, $exludedClass) === false) {
                $model->createAudit('deleted');
            }
        });
    }

    protected function createAudit(string $event): void
    {
        $user = Auth::user();
        // Log::debug('[ROUTE NAME]: ' . print_r($this->getOriginal()['route_name'], true));
        // Log::debug('[PREVIOUS]: ' . print_r($this->getPrevious(), true));
        // Log::debug('[CHANGES]: ' . print_r($this->getChanges(), true));

        $data = [
            'user_id'           => $user?->id,
            'event'             => $event,
            'auditable_type'    => static::class,
            'auditable_id'      => $this->getKey(),
            'old_values'        => $event === 'updated' ? $this->getOriginal() : null,
            'changed_values'    => $event === 'updated' ? $this->getChanges() : null,
            'new_values'        => $event !== 'deleted' ? $this->getAttributes() : null,
            'message'           => sprintf('%s is %s.', $this->model, $event),
            'ip_address'        => Request::ip(),
        ];

        Audit::create($data);
    }
}
