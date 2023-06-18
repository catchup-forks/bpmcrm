<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UsersFactory extends Factory
{
    protected $model = User::class;

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
