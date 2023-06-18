<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * @return array{username: string, email: string, password: string, status: mixed, firstname: string, lastname: string, address: string, city: string, state: mixed, postal: string, phone: string, fax: string, cell: string, title: string, birthdate: DateTime, timezone: string, datetime_format: mixed, language: string, loggedin_at: null, is_administrator: false}
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->email,
            'password' => Hash::make('password'),

            'status' => $this->faker->randomElement(['ACTIVE', 'INACTIVE']),

            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
            'postal' => $this->faker->postcode,

            'phone' => $this->faker->phoneNumber,
            'fax' => $this->faker->phoneNumber,
            'cell' => $this->faker->phoneNumber,

            'title' => $this->faker->jobTitle,
            'birthdate' => $this->faker->dateTimeThisCentury,
            'timezone' => $this->faker->timezone,
            'datetime_format' => $this->faker->randomElement([null, 'm/d/Y', 'm/d/Y h:i A', 'm/d/Y H:i']),
            'language' => 'us_en',
            'loggedin_at' => null,

            'is_administrator' => false,

        ];
    }
}
