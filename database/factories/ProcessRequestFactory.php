<?php

use Faker\Generator as Faker;
use App\Models\Process;
use App\Models\ProcessCollaboration;
use App\Models\ProcessRequest;
use App\Models\User;

/**
 * Model factory for a process request
 */
$factory->define(ProcessRequest::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'data' => '{}',
        'status' => $faker->randomElement(['DRAFT', 'ACTIVE', 'COMPLETED']),
        'callable_id' => $faker->randomDigit,
        'user_id' => function () {
            return factory(User::class)->create()->getKey();
        },
        'process_id' => function () {
            return factory(Process::class)->create()->getKey();
        },
        'process_collaboration_id' => function () {
            return factory(ProcessCollaboration::class)->create()->getKey();
        }
    ];
});
