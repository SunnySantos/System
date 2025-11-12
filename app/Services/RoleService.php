<?php

namespace App\Services;

use App\Models\Role;
use App\Models\RoleAccess;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class RoleService
{
    public function getPaginatedRole(Request $request): LengthAwarePaginator
    {
        $user = Auth::user();
        $roleId = $user->role_id;

        $query = Role::search($request)->withCount('users');

        if ($roleId != Role::SUPER_ADMIN_ID) {
            $query->where('id', '!=', Role::SUPER_ADMIN_ID);
        }

        return $query->paginate(5)->withQueryString();
    }

    public function getAuthenticatedRoutes(): array
    {
        return collect(Route::getRoutes())
            ->filter(fn($route) => in_array('auth', $route->gatherMiddleware())) // only routes that require authentication
            ->mapWithKeys(function ($route) {
                $name = $route->getName();

                if (!$name) {
                    return [];
                }

                $parts = explode('.', $name);
                [$module, $action] = array_pad($parts, 2, null);

                // Validate module and action
                if (!in_array($module, Role::MODULES) || !in_array($action, array_keys(Role::ROUTE_ACTIONS))) {
                    return [];
                }

                return [
                    $name => [
                        'label' => Role::ROUTE_ACTIONS[$action] . " " . ucfirst($module),
                        'module' => $module,
                        'can_access' => false,
                    ]
                ];
            })
            ->filter() // remove null route names
            ->all();
    }

    /**
     * Create a new role and its related route accesses.
     *
     * @param  array  $validated
     * @return \App\Models\Role
     */
    public function createRoleWithAccesses(array $validated): Role
    {
        return DB::transaction(function () use ($validated) {

            // Create role
            $role = Role::create([
                'name' => $validated['name'],
            ]);

            /* 
            *   Get selected routes from form (checked checkboxes)
            *   Get all authenticated routes
            */
            [$selectedRoutes, $authenticatedRoutes] = $this->expandRouteRelations($validated['role_accesses'] ?? [], Role::getAuthenticatedRoutes());

            // Bulk insert accesses
            $role->accesses()->createMany(
                collect($authenticatedRoutes)->map(function ($route) use ($selectedRoutes) {
                    return [
                        'route_name' => $route,
                        'can_access' => in_array($route, $selectedRoutes, true),
                    ];
                })->toArray()
            );

            return $role;
        });
    }


    /**
     * Update a role and its related route accesses.
     *
     * @param  Role $role
     * @param  array  $validated
     * @return \App\Models\Role
     */
    public function updateRoleWithAccesses(Role $role, array $validated): Role
    {
        return DB::transaction(function () use ($role, $validated) {

            // Update role name
            $role->update([
                'name' => $validated['name'],
            ]);

            /* 
            *   Get selected routes from form (checked checkboxes)
            *   Get all authenticated routes
            */
            [$selectedRoutes, $authenticatedRoutes] = $this->expandRouteRelations($validated['role_accesses'] ?? [], Role::getAuthenticatedRoutes());

            // Get existing accesses from database
            $existingAccesses = $role->accesses()->pluck('can_access', 'route_name');


            // STEP 1: Add or update each valid route
            foreach ($authenticatedRoutes as $route) {
                $canAccess = in_array($route, $selectedRoutes, true);

                if ($existingAccesses->has($route)) {
                    // Update only if something changed
                    if ($existingAccesses[$route] != $canAccess) {
                        RoleAccess::where('role_id', $role->id)
                            ->where('route_name', $route)
                            ->first()
                            ?->update(['can_access' => $canAccess]);
                    }
                } else {
                    // Create new route access entry
                    $role->accesses()->create([
                        'route_name' => $route,
                        'can_access' => $canAccess,
                    ]);

                    // RoleAccess::create([
                    //     'role_id'       => $role->id,
                    //     'route_name'    => $route,
                    //     'can_access'    => $canAccess,
                    // ]);
                }
            }

            // STEP 2: Remove routes that no longer exist in your app
            $toDelete = $existingAccesses->keys()->diff($authenticatedRoutes);
            if ($toDelete->isNotEmpty()) {
                $role->accesses()->whereIn('route_name', $toDelete)->delete();
            }

            Role::clearAuthenticatedRoutesCache();

            return $role;
        });
    }


    /**
     * Bulk delete multiple roles by their IDs.
     *
     * @param  array<int>  $ids
     * @return int  Number of roles deleted
     */
    public function bulkDelete(array $ids): void
    {
        DB::transaction(function () use ($ids) {
            Role::deleteMany($ids);
        });
    }

    public function updateUserRoleAfterDelete(int $oldRoleId, int $newRoleId): void
    {
        DB::transaction(function () use ($oldRoleId, $newRoleId) {
            // Reassign all users with the old role
            User::where('role_id', $oldRoleId)->update([
                'role_id' => $newRoleId,
            ]);

            // Delete the old role
            Role::deleteMany([$oldRoleId]);
        });
    }

    protected function expandRouteRelations(array $selectedRoutes, array $authenticatedRoutes): array
    {
        foreach ($selectedRoutes as $route) {
            [$module, $action] = array_pad(explode('.', $route, 2), 2, null);

            match (true) {
                $module == 'settings' && $action == 'index' => $this->pushRoutes($selectedRoutes, $authenticatedRoutes, 'password.confirm'),
                $action == 'create' => $this->pushRoutes($selectedRoutes, $authenticatedRoutes, "$module.store"),
                $action == 'edit' => $this->pushRoutes($selectedRoutes, $authenticatedRoutes, "$module.update"),
                $action == 'destroy' => $this->pushRoutes($selectedRoutes, $authenticatedRoutes, "$module.bulk-delete", ...($module == 'roles' ? ["$module.update-user-role"] : [])),
                default => null
            };
        }

        return [array_unique($selectedRoutes), array_unique($authenticatedRoutes)];
    }

    protected function pushRoutes(array &$selected, array &$auth, string ...$routes): void
    {
        foreach ($routes as $route) {
            $selected[] = $route;
            $auth[] = $route;
        }
    }
}
