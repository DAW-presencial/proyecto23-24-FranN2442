<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Restaurant;

class RestaurantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Restaurant::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'postal_code' => $this->faker->postcode(),
            'location' => 'Calaratjada',
            'category' => 'Fast Food',
            'tel_num' => $this->faker->regexify('[A-Za-z0-9]{9}'),
            'email' => $this->faker->safeEmail(),
            'password' => Hash::make('password'),
            'tourns' => '{"tourn" : {"start" : "12:00", "end" : "24:00"}}',
            'description' => 'Default Description',
            'capacity' => $this->faker->regexify('[0-9]{2}'),
        ];
    }
}
