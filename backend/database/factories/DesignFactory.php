<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Design;

class DesignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Design::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'hall_name' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'tables' => '{}',
            'restaurant_id' => Restaurant::first()->getRouteKey()
        ];
    }
}
