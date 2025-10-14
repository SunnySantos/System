<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleAccess;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('name', 'Super Admin')->first();
        if (!$role) {
            $this->command->info('Super Admin role not found. Please run RoleSeeder first.');
            return;
        }

        $roleId = $role->id;

        $permissions = [
            'dashboard.index',
            'users.index',
            'users.store',
            'users.bulk-delete',
            'users.create',
            'users.show',
            'users.update',
            'users.destroy',
            'users.edit',
            'roles.index',
            'roles.store',
            'roles.bulk-delete',
            'roles.create',
            'roles.show',
            'roles.update',
            'roles.destroy',
            'roles.edit',
            'settings.index',
        ];

        foreach ($permissions as $permission) {
            RoleAccess::firstOrCreate([
                'role_id' => $roleId,
                'route_name' => $permission,
                'can_access' => true,
            ]);
        }
    }
}
