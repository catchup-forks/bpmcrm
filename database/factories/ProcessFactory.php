<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Process;
use App\Models\ProcessCategory;
use App\Models\User;

class ProcessFactory extends Factory
{
    protected $model = Process::class;
    /**
     * @return array{name: string, bpmn: string|false, description: string, status: mixed, user_id: Closure, process_category_id: Closure}
     */
    public function definition()
    {
        $emptyProcess = $this->faker->file(Process::getProcessTemplatesPath());
        return [
            'name' => $this->faker->unique()->sentence(3),
            'bpmn' => file_get_contents($emptyProcess),
            'description' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement(['ACTIVE', 'INACTIVE']),
            'user_id' => function () {
                return User::factory()->create()->getKey();
            },
            'process_category_id' => function () {
                return ProcessCategory::factory()->create()->getKey();
            }
        ];
    }
}
