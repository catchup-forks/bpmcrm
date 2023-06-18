<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProcessCategory;

class ProcessCategoryFactory extends Factory
{
    protected $model = ProcessCategory::class;
    /**
     * @return array{name: string, status: mixed}
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->sentence(),
            'status' => $this->faker->randomElement(
                ['ACTIVE', 'INACTIVE']
            )
        ];
    }
}
