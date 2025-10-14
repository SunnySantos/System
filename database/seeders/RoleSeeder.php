<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::factory()->count(5)->create();

        $roles->each(function ($role) {
            // Assign all permissions to 'Super Admin'
            if ($role->name === 'Super Admin') {
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
                    $role->accesses()->create(['route_name' => $permission]);
                }
            }
        });
    }
}
