<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoleAccess>
 */
class RoleAccessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role_id' => Role::inRandomOrder()->first()?->id ?? Role::factory(),
            'route_name' => $this->faker->unique()->randomElement([
                'dashboard.index',
                'users.index',
                'users.store',
                'users.bulk-delete',
                'users.create',
                'users.show',
                'users.update',
                'users.destroy',
                'users.edit',
                'settings.index',
            ]),
            'can_access' => $this->faker->boolean(90), // 90% chance of true
        ];
    }
}
