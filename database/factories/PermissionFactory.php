<?php

namespace Database\Factories;

use Faker\Generator as Faker;

$factory->define(app\Models\Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(2),
        'guard_name' => $faker->sentence(2),
        'description' => $faker->sentence(5),
    ];
});
