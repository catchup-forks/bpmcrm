<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use App\Models\Script;

$factory->define(Script::class, function (Faker $faker) {
    return [
        'key' => null,
        'title' => $faker->sentence,
        'language' => $faker->randomElement(['php', 'lua']),
        'code' => $faker->sentence($faker->randomDigitNotNull),
        'description' => $faker->sentence
    ];
});
