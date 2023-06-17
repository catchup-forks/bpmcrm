<?php

use Faker\Generator as Faker;
use App\Models\ProcessCategory;

/**
 * Model factory for a process category.
 */
$factory->define(ProcessCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->sentence(),
        'status' => $faker->randomElement(
            ['ACTIVE', 'INACTIVE']
        )
    ];
});
