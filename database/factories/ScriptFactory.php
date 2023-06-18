<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Script;

class ScriptFactory extends Factory
{
    protected $model = Script::class;
    /**
     * @return array{key: null, title: string, language: mixed, code: string, description: string}
     */
    public function definition(): array
    {
        return [
            'key' => null,
            'title' => $this->faker->sentence,
            'language' => $this->faker->randomElement(['php', 'lua']),
            'code' => $this->faker->sentence($this->faker->randomDigitNotNull),
            'description' => $this->faker->sentence
        ];
    }
}
