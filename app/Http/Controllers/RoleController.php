<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\BulkDeleteRoleRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Requests\Role\UpdateUserRoleAfterDeleteRequest;
use App\Models\Role;
use App\Models\RoleAccess;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $roleId = $user->role_id;

        if ($roleId == 1) {
            $roles = Role::search($request)->withCount('users')->paginate(5)->withQueryString();
        } else {
            $roles = Role::search($request)->where('id', '!=', 1)->withCount('users')->paginate(5)->withQueryString();
        }

        return view('roles.index', compact('roles', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authenticatedRoutes = collect(Route::getRoutes())
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

        return view('roles.create', [
            'modules' => Role::MODULES,
            'authenticatedRoutes' => $authenticatedRoutes,
        ]);

        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $role = Role::create([
            'name' => $validated['name'],
        ]);

        // Get all authenticated routes
        $authenticatedRoutes = Role::getAuthenticatedRoutes();

        // Get selected routes from form (checked checkboxes)
        $selectedRoutes = $validated['role_accesses'] ?? [];

        foreach ($selectedRoutes as $route) {
            [$module, $action] = array_pad(explode('.', $route, 2), 2, null);

            if ($module == 'settings' && $action == 'index') {
                array_push($selectedRoutes, 'password.confirm');
                array_push($authenticatedRoutes, 'password.confirm');
            } elseif ($action == 'create') {
                array_push($selectedRoutes, $module . '.store');
                array_push($authenticatedRoutes, $module . '.store');
            } elseif ($action == 'edit') {
                array_push($selectedRoutes, $module . '.update');
                array_push($authenticatedRoutes, $module . '.update');
            } elseif ($action == 'destroy') {
                array_push($selectedRoutes, $module . '.bulk-delete');
                array_push($authenticatedRoutes, $module . '.bulk-delete');

                if ($module == 'roles') {
                    array_push($selectedRoutes, $module . '.update-user-role');
                    array_push($authenticatedRoutes, $module . '.update-user-role');
                }
            }
        }

        $selectedRoutes = array_unique($selectedRoutes);
        $authenticatedRoutes = array_unique($authenticatedRoutes);

        // Prepare accesses
        $roleAccesses = collect($authenticatedRoutes)->map(function ($route) use ($selectedRoutes) {
            return [
                'route_name' => $route,
                'can_access' => in_array($route, $selectedRoutes, true),
            ];
        });

        // Bulk insert for efficiency
        $role->accesses()->createMany(
            $roleAccesses->map(fn($access) => [
                'route_name' => $access['route_name'],
                'can_access' => $access['can_access'],
            ])->toArray()
        );

        return redirect()->route('roles.index')->with('success', 'Role created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $authenticatedRoutes = $role->accessedRoutes();

        return view('roles.show', [
            'role' => $role,
            'modules' => Role::MODULES,
            'authenticatedRoutes' => $authenticatedRoutes,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $authenticatedRoutes = $role->accessedRoutes();

        return view('roles.edit', [
            'role' => $role,
            'modules' => Role::MODULES,
            'authenticatedRoutes' => $authenticatedRoutes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validated = $request->validated();

        // Update role name
        $role->update([
            'name' => $validated['name'],
        ]);

        // Get all authenticated routes
        $authenticatedRoutes = Role::getAuthenticatedRoutes();

        // Get selected routes from form (checked checkboxes)
        $selectedRoutes = $validated['role_accesses'] ?? [];

        foreach ($selectedRoutes as $route) {
            [$module, $action] = array_pad(explode('.', $route, 2), 2, null);

            if ($module == 'settings' && $action == 'index') {
                array_push($selectedRoutes, 'password.confirm');
                array_push($authenticatedRoutes, 'password.confirm');
            } elseif ($action == 'create') {
                array_push($selectedRoutes, $module . '.store');
                array_push($authenticatedRoutes, $module . '.store');
            } elseif ($action == 'edit') {
                array_push($selectedRoutes, $module . '.update');
                array_push($authenticatedRoutes, $module . '.update');
            } elseif ($action == 'destroy') {
                array_push($selectedRoutes, $module . '.bulk-delete');
                array_push($authenticatedRoutes, $module . '.bulk-delete');

                if ($module == 'roles') {
                    array_push($selectedRoutes, $module . '.update-user-role');
                    array_push($authenticatedRoutes, $module . '.update-user-role');
                }
            }
        }

        $selectedRoutes = array_unique($selectedRoutes);
        $authenticatedRoutes = array_unique($authenticatedRoutes);

        // Get existing accesses from database
        $existingAccesses = $role->accesses()->pluck('can_access', 'route_name');

        // Start a transaction to ensure atomic updates
        DB::transaction(function () use ($role, $authenticatedRoutes, $selectedRoutes, $existingAccesses) {
            // STEP 1: Add or update each valid route
            foreach ($authenticatedRoutes as $route) {
                $canAccess = in_array($route, $selectedRoutes, true);

                if ($existingAccesses->has($route)) {
                    // Update only if something changed
                    if ($existingAccesses[$route] !== $canAccess) {
                        $role->accesses()
                            ->where('route_name', $route)
                            ->update(['can_access' => $canAccess]);
                    }
                } else {
                    // Create new route access entry
                    $role->accesses()->create([
                        'route_name' => $route,
                        'can_access' => $canAccess,
                    ]);
                }
            }

            // STEP 2: Remove routes that no longer exist in your app
            $toDelete = $existingAccesses->keys()->diff($authenticatedRoutes);
            if ($toDelete->isNotEmpty()) {
                $role->accesses()->whereIn('route_name', $toDelete)->delete();
            }
        });


        return redirect()->route('roles.show', $role->id)->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
    }


    public function bulkDelete(BulkDeleteRoleRequest $request): RedirectResponse
    {
        $ids = explode(',', $request->ids);
        $singular = 'role';
        $plural = 'roles';

        Role::whereIn('id', $ids)->delete();

        return back()->with('success', 'Selected ' . (sizeof($ids) > 1 ? $plural : $singular) . ' deleted successfully!');
    }

    public function updateUserRoleAfterDelete(UpdateUserRoleAfterDeleteRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $oldRoleId = $validated['role_id'];
        $newRoleId = $validated['new_role_id'];

        DB::transaction(function () use ($oldRoleId, $newRoleId) {
            // Reassign all users with the old role
            User::where('role_id', $oldRoleId)->update([
                'role_id' => $newRoleId,
            ]);

            // Delete the old role
            Role::where('id', $oldRoleId)->delete();
        });


        return redirect()->route('roles.index')->with('success', 'Role deleted and users reassigned successfully.');
    }
}
