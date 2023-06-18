<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ScreenCategory;

class ScreenCategoryFactory extends Factory
{
    protected $model = ScreenCategory::class;
    /**
     * @return array{name: string, status: mixed}
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->sentence(),
            'status' => $this->faker->randomElement(
                ['ACTIVE', 'INACTIVE']
            )
        ];
    }
}
