<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EnvironmentVariable;

class EnvironmentVariableFactory extends Factory
{
    protected $model = EnvironmentVariable::class;
    /**
     * @return array{name: string, description: string, value: string}
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'value' => $this->faker->sentence
        ];
    }
}
