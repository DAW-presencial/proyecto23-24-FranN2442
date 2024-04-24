<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Restaurant;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->email,
            'pin' => '2442',
            'tel_num' => '657911913',
            'role' => $this->faker->randomElement(['admin','employee']),
            'restaurant_id' => Restaurant::first()->getRouteKey(),
        ];
    }
}
