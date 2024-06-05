<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Design;
use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\User;

class ReservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'reservation_code' => $this->faker->regexify('[A-Za-z0-9]{12}'),
            'date' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'hour' => $this->faker->time,
            'old_hour' => null,
            'diners' => parse_str($this->faker->numberBetween(0,100)),
            'table_number' => $this->faker->regexify('[0-9]{4}'),
            'user_id' => User::factory(),
            'restaurant_id' => Restaurant::factory(),
            'design_id' => Design::factory(),
        ];
    }
}
