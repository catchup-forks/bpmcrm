<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\app\Models\Permission;
class PermissionFactory extends Factory
{
    protected $model = Permission::class;
    /**
     * @return array{name: string, guard_name: string, description: string}
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'guard_name' => $this->faker->sentence(2),
            'description' => $this->faker->sentence(5),
        ];
    }
}
