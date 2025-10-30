<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\BulkDeleteRoleRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Requests\Role\UpdateUserRoleAfterDeleteRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $roles = $this->roleService->getPaginatedRole($request);

        return view('roles.index', compact('roles', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create', [
            'modules' => Role::MODULES,
            'authenticatedRoutes' => $this->roleService->getAuthenticatedRoutes(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = $this->roleService->createRoleWithAccesses($request->validated());

        return redirect()->route('roles.index')->with('success', "Role '{$role->name}' created successfully!");
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
        $role = $this->roleService->updateRoleWithAccesses($role, $request->validated());

        return redirect()->route('roles.show', $role->id)->with('success', "Role '{$role->name}' updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', "Role '{$role->name}' deleted successfully!");
    }


    public function bulkDelete(BulkDeleteRoleRequest $request): RedirectResponse
    {
        $ids = explode(',', $request->ids);

        $this->roleService->bulkDelete($ids);

        return back()->with('success', 'Selected ' . (count($ids) > 1 ? 'roles' : 'role') . ' deleted successfully!');
    }

    public function updateUserRoleAfterDelete(UpdateUserRoleAfterDeleteRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->roleService->updateUserRoleAfterDelete(
            $validated['role_id'],
            $validated['new_role_id']
        );

        return redirect()->route('roles.index')->with('success', 'Role deleted and users reassigned successfully.');
    }
}
