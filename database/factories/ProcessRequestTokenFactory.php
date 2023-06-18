<?php

namespace Database\Factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Process;
use App\Models\ProcessRequest;
use App\Models\ProcessRequestToken;
use App\Models\User;

class ProcessRequestTokenFactory extends Factory
{
    protected $model = ProcessRequestToken::class;
    /**
     * @return array{element_type: string, element_id: mixed, element_name: string, status: mixed, process_id: Closure, process_request_id: Closure, user_id: Closure, completed_at: DateTime, due_at: DateTime, initiated_at: DateTime, riskchanges_at: DateTime}
     */
    public function definition()
    {
        return [
            'element_type' => 'TASK',
            'element_id' => $this->faker->randomDigit,
            'element_name' => $this->faker->name,
            'status' => $this->faker->randomElement(['ACTIVE','FAILING','COMPLETED','CLOSED','EVENT_CATCH']),
            'process_id' => function () {
                return Process::factory()->create()->getKey();
            },
            'process_request_id' => function () {
                return ProcessRequest::factory()->create()->getKey();
            },
            'user_id' => function () {
                return User::factory()->create()->getKey();
            },
            'completed_at' => $this->faker->dateTime,
            'due_at' => $this->faker->dateTime,
            'initiated_at' => $this->faker->dateTime,
            'riskchanges_at' => $this->faker->dateTime,
        ];
    }
}
