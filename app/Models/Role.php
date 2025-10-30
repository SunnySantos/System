<?php

namespace App\Models;

use App\Traits\HasAuthenticatedRoutes;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Role extends BaseModel
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory, Searchable, HasAuthenticatedRoutes;

    public const MODULES = ['dashboard', 'users', 'roles', 'settings', 'audit'];
    public const ROUTE_ACTIONS = [
        'index' => 'View All',
        'show' => 'View',
        'create' => 'Create',
        'edit' => 'Edit',
        'destroy' => 'Delete',
    ];
    public const SUPER_ADMIN_ID = 1;
    protected const SEARCHABLE_COLUMNS = ['name'];
    protected const ALLOWED_SORTS = ['id', 'name'];
    protected const ALLOWED_SORT_DIRECTIONS = ['asc', 'desc'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    protected $model = 'Role';

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

        return Cache::remember($cacheKey, 3600, function () {
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
        });
    }

    public static function clearAuthenticatedRoutesCache(): void
    {
        foreach (Role::all() as $role) {
            Cache::forget("role_{$role->id}_accessed_routes");
        }

        Cache::forget('authenticated_routes_' . md5(json_encode(static::MODULES)));
    }
}
