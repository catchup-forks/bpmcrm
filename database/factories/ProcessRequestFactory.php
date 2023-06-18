<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Process;
use App\Models\ProcessCollaboration;
use App\Models\ProcessRequest;
use App\Models\User;

class ProcessRequestFactory extends Factory
{
    protected $model = ProcessRequest::class;
    /**
     * @return array{name: string, data: string, status: mixed, callable_id: mixed, user_id: Closure, process_id: Closure, process_collaboration_id: Closure}
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'data' => '{}',
            'status' => $this->faker->randomElement(['DRAFT', 'ACTIVE', 'COMPLETED']),
            'callable_id' => $this->faker->randomDigit,
            'user_id' => function () {
                return User::factory()->create()->getKey();
            },
            'process_id' => function () {
                return Process::factory()->create()->getKey();
            },
            'process_collaboration_id' => function () {
                return ProcessCollaboration::factory()->create()->getKey();
            }
        ];
    }
}
