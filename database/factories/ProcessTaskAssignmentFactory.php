<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Group;
use App\Models\Process;
use App\Models\ProcessTaskAssignment;
use App\Models\User;

/**
 * Model factory for a Process Task Assignment
 */

use Faker\Generator as Faker;

$factory->defineAs(ProcessTaskAssignment::class, 'user', function (Faker $faker) use ($factory): array {
    $follow = $factory->raw(ProcessTaskAssignment::class);
    $extras = [
        'id' => function () {
            return User::factory()->create()->getKey();
        },
        'assignment_type' => User::class 
    ];
    return array_merge($follow, $extras);
});

$factory->defineAs(ProcessTaskAssignment::class, 'group', function (Faker $faker) use ($factory): array {
    $follow = $factory->raw(ProcessTaskAssignment::class);
    $extras = [
        'id' => function () {
            return Group::factory()->create()->getKey();
        },
        'assignment_type' => Group::class
    ];
    return array_merge($follow, $extras);
});
class ProcessTaskAssignmentFactory extends Factory
{
    protected $model = ProcessTaskAssignment::class;
    /**
     * @return array{process_id: Closure, process_task_id: mixed, assignment_id: mixed, assignment_type: class-string|false}
     */
    public function definition()
    {
        $model = factory($this->faker->randomElement([
            User::class,
            Group::class,
        ]))->create();
        return [
            'process_id' => function () {
                return Process::factory()->create()->getKey();
            },
            'process_task_id' => $this->faker->randomDigit,
            'assignment_id' => $model->getKey(),
            'assignment_type' => get_class($model)
        ];
    }
}
