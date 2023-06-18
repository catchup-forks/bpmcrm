<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use App\Models\ProcessVersion;
use App\Models\Process;
use App\Models\ProcessCategory;

/**
 * Model factory for a ProcessVersion
 */
$factory->define(ProcessVersion::class, function (Faker $faker) {
    $emptyProcess = $faker->file(Process::getProcessTemplatesPath());
    return [
        'bpmn' => file_get_contents($emptyProcess),
        'name' => $faker->sentence(3),
        'status' => $faker->randomElement(['ACTIVE', 'INACTIVE']),
        'process_category_id' => function () {
            return factory(ProcessCategory::class)->create()->getKey();
        },
        'process_id' => function () {
            return factory(Process::class)->create()->getKey();
        }
    ];
});
