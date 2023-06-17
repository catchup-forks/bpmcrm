<?php

use Faker\Generator as Faker;
use App\Models\Process;
use App\Models\ProcessCategory;
use App\Models\User;

/**
 * Model factory for a process
 */
$factory->define(Process::class, function (Faker $faker) {
    $emptyProcess = $faker->file(Process::getProcessTemplatesPath());
    return [
        'name' => $faker->unique()->sentence(3),
        'bpmn' => file_get_contents($emptyProcess),
        'description' => $faker->paragraph(3),
        'status' => $faker->randomElement(['ACTIVE', 'INACTIVE']),
        'user_id' => function () {
            return factory(User::class)->create()->getKey();
        },
        'process_category_id' => function () {
            return factory(ProcessCategory::class)->create()->getKey();
        }
    ];
});
