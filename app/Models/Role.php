<?php

namespace App\Models;

use App\Traits\HasAuthenticatedRoutes;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory, Searchable, HasAuthenticatedRoutes;

    public const MODULES = ['dashboard', 'users', 'roles', 'settings'];
    public const ROUTE_ACTIONS = [
        'index' => 'View All',
        'show' => 'View',
        'create' => 'Create',
        'edit' => 'Edit',
        'destroy' => 'Delete',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * A Role has many RoleAccess.
     *
     * @return HasMany<RoleAccess>
     */
    public function accesses(): HasMany
    {
        return $this->hasMany(RoleAccess::class);
    }

    /**
     * Get an array of route names that the role has access to.
     *
     * @return array<int, string>
     */
    public function accessRouteNames(): array
    {
        return $this->accesses->where('can_access', true)->pluck('route_name')->toArray();
    }

    /**
     * A Role has many User.
     *
     * @return HasMany<User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all authenticated routes with access info for this role.
     *
     * @return array
     */
    public function accessedRoutes(): array
    {
        // Cache per role to avoid re-processing every time
        $cacheKey = "role_{$this->id}_accessed_routes";

        // return Cache::remember($cacheKey, 3600, function () {
        return collect(static::getAuthenticatedRoutes())
            ->mapWithKeys(function ($route) {
                [$module, $action] = array_pad(explode('.', $route, 2), 2, null);

                return [
                    $route => [
                        'label' => sprintf('%s %s', static::ROUTE_ACTIONS[$action] ?? ucfirst($action), ucfirst($module)),
                        'module' => $module,
                        'action' => $action,
                        'can_access' => in_array($route, $this->accessRouteNames(), true),
                    ],
                ];
            })
            ->toArray();
        // });
    }
}
