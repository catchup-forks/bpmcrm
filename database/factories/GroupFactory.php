<?php

use Faker\Generator as Faker;
use App\Models\Group;

/**
 * Model factory for a Group
 */
$factory->define(Group::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'description' => $faker->sentence,
        'status' => $faker->randomElement(['ACTIVE', 'INACTIVE']),
    ];
});
