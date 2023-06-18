<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use App\Models\Screen;
use App\Models\ScreenCategory;

/**
 * Model factory for a screen.
 */
$factory->define(Screen::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(3),
        'description' => $faker->sentence(5),
        'screen_category_id' => function () {
            return factory(ScreenCategory::class)->create()->getKey();
        }
    ];
});
