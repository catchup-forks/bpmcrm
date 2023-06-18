<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use App\Models\Process;
use App\Models\ProcessRequest;
use App\Models\ProcessRequestToken;
use App\Models\User;

/**
 * Model factory for a ProcessRequestToken
 */
$factory->define(ProcessRequestToken::class, function (Faker $faker) {
    return [
        'element_type' => 'TASK',
        'element_id' => $faker->randomDigit,
        'element_name' => $faker->name,
        'status' => $faker->randomElement(['ACTIVE','FAILING','COMPLETED','CLOSED','EVENT_CATCH']),
        'process_id' => function () {
            return factory(Process::class)->create()->getKey();
        },
        'process_request_id' => function () {
            return factory(ProcessRequest::class)->create()->getKey();
        },
        'user_id' => function () {
            return factory(User::class)->create()->getKey();
        },
        'completed_at' => $faker->dateTime,
        'due_at' => $faker->dateTime,
        'initiated_at' => $faker->dateTime,
        'riskchanges_at' => $faker->dateTime,
    ];
});
