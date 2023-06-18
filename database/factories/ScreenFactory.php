<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Screen;
use App\Models\ScreenCategory;

class ScreenFactory extends Factory
{
    protected $model = Screen::class;
    /**
     * @return array{title: string, description: string, screen_category_id: Closure}
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(5),
            'screen_category_id' => function () {
                return ScreenCategory::factory()->create()->getKey();
            }
        ];
    }
}
