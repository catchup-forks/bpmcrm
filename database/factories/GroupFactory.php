<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Group;

class GroupFactory extends Factory
{
    protected $model = Group::class;
    /**
     * @return array{name: string, description: string, status: mixed}
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['ACTIVE', 'INACTIVE']),
        ];
    }
}
