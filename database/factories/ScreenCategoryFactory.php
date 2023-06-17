<?php

use Faker\Generator as Faker;
use App\Models\ScreenCategory;

/**
 * Model factory for a screen category.
 */
$factory->define(ScreenCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->sentence(),
        'status' => $faker->randomElement(
            ['ACTIVE', 'INACTIVE']
        )
    ];
});
