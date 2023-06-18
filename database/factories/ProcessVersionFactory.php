<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProcessVersion;
use App\Models\Process;
use App\Models\ProcessCategory;

class ProcessVersionFactory extends Factory
{
    protected $model = ProcessVersion::class;
    /**
     * @return array{bpmn: string|false, name: string, status: mixed, process_category_id: Closure, process_id: Closure}
     */
    public function definition(): array
    {
        $emptyProcess = $this->faker->file(Process::getProcessTemplatesPath());
        return [
            'bpmn' => file_get_contents($emptyProcess),
            'name' => $this->faker->sentence(3),
            'status' => $this->faker->randomElement(['ACTIVE', 'INACTIVE']),
            'process_category_id' => function () {
                return ProcessCategory::factory()->create()->getKey();
            },
            'process_id' => function () {
                return Process::factory()->create()->getKey();
            }
        ];
    }
}
