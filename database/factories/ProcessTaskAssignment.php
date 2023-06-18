<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Process;
use App\Models\ProcessTaskAssignment;
use App\Models\User;

/**
 * Model factory for a Process Task Assignment
 */

use Faker\Generator as Faker;

$factory->define(ProcessTaskAssignment::class, function (Faker $faker) {

    $model = factory($faker->randomElement([
        User::class,
        Group::class,
    ]))->create();

    return [
        'process_id' => function () {
            return factory(Process::class)->create()->getKey();
        },
        'process_task_id' => $faker->randomDigit,
        'assignment_id' => $model->getKey(),
        'assignment_type' => get_class($model)
    ];
});

$factory->defineAs(ProcessTaskAssignment::class, 'user', function (Faker $faker) use ($factory) {
    $follow = $factory->raw(ProcessTaskAssignment::class);
    $extras = [
        'id' => function () {
            return factory(User::class)->create()->getKey();
        },
        'assignment_type' => User::class 
    ];
    return array_merge($follow, $extras);
});

$factory->defineAs(ProcessTaskAssignment::class, 'group', function (Faker $faker) use ($factory) {
    $follow = $factory->raw(ProcessTaskAssignment::class);
    $extras = [
        'id' => function () {
            return factory(Group::class)->create()->getKey();
        },
        'assignment_type' => Group::class
    ];
    return array_merge($follow, $extras);
});
