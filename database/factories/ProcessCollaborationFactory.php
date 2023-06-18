<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Process;
use App\Models\ProcessCollaboration;

class ProcessCollaborationFactory extends Factory
{
    protected $model = ProcessCollaboration::class;
    /**
     * @return array{process_id: Closure}
     */
    public function definition(): array
    {
        return [
            'process_id' => function () {
                return Process::factory()->create()->getKey();
            },
        ];
    }
}
